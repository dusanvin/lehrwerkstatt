<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;

use Illuminate\Auth\Events\Registered;

use Illuminate\Support\Facades\Auth;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller

{

    public function index(Request $request)

    {

        $data = User::orderBy('id', 'DESC')->simplePaginate(50);

        return view('users.index', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()

    {

        $roles = Role::pluck('name', 'name')->all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)

    {

        $this->validate($request, [
            'vorname' => 'required',
            'nachname' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);

        $role = $request->input('roles');
        $role_id = 0;
        if ($role[0] == 'Admin') {
            $role_id = 7;
        }
        if ($role[0] == 'Helfende') {
            $role_id = 4;
        }
        if ($role[0] == 'Lehrende') {
            $role_id = 5;
        }
        if ($role[0] == 'Moderierende') {
            $role_id = 3;
        }

        DB::insert('insert into model_has_roles (role_id, model_type, model_id) values (?, ?, ?)', [$role_id, 'App\Models\User', $user->id]);

        event(new Registered($user));

        $user->timestamps = false;
        $user->last_login_at = now();

        return redirect()->route('users.index')
            ->with('success', 'Person erfolgreich erstellt.');
    }

    public function show($id)

    {

        $user = User::find($id);
        $user->survey_data = json_decode($user->survey_data);
        return view('users.show', compact('user'));
    }


    public function edit($id)

    {
        $user = User::find($id);
        $user->survey_data = json_decode($user->survey_data);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('users.edit', compact('user', 'roles', 'userRole'));
    }


    public function update(Request $request, $id)

    {

        // Nach Klick auf "??nderungen ??bernehmen"

        $this->validate($request, [
            'vorname' => 'required',
            'nachname' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
        ]);



        $input = $request->all();

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }



        $user = User::find($id);
        $user->survey_data = json_decode($user->survey_data);
        // $user->update($input);
        $user->survey_data->vorname = $input['vorname'];
        $user->survey_data->nachname = $input['nachname'];
        $user->survey_data->email = $input['email'];
        if (isset($input['password']))
            $user->password = $input['password'];

        $user->survey_data = json_encode($user->survey_data);
        $user->save();

        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'Informationen erfolgreich aktualisiert.');
    }


    public function destroy($id)

    {

        User::find($id)->delete();

        return redirect()->route('users.index')

            ->with('success', 'Person erfolgreich gel??scht.');
    }

    public function save(Request $request)
    {
        $id = Auth::id();
        $user = User::find($id);

        $user->vorname = $request->survey['vorname'];
        $user->nachname = $request->survey['nachname'];
        $user->survey_data = $request->survey;
        $user->valid = true;

        $user->save();
    }


    private function mse($lehr, $stud)
    {
        $sum = 0;
        $sum += ($lehr->survey_data->feedback_an - $stud->survey_data->feedback_von) ** 2;
        $sum += ($lehr->survey_data->feedback_von - $stud->survey_data->feedback_an) ** 2;
        $sum += (($lehr->survey_data->eigenstaendigkeit - $stud->survey_data->eigenstaendigkeit) ** 2) * 2;
        $sum += ($lehr->survey_data->improvisation - $stud->survey_data->improvisation) ** 2;
        $sum += ($lehr->survey_data->freiraum - $stud->survey_data->freiraum) ** 2;
        $sum += ($lehr->survey_data->innovationsoffenheit - $stud->survey_data->innovationsoffenheit) ** 2;
        $sum += (($lehr->survey_data->belastbarkeit - $stud->survey_data->belastbarkeit) ** 2) * 2;
        return number_format($sum / 9.0, 2);
    }

    public function compareMatchings($matching1, $matching2)
    {
        if ($matching1->mse == $matching2->mse)
            return 0;

        if ($matching1->mse < $matching2->mse)
            return -1;
        else
            return 1;
    }

    public function matchings(Request $request)
    {
        $lehr = User::where('role', 'lehr')->where('valid', true)->where('assigned', false)->get();
        $stud = User::where('role', 'stud')->where('valid', true)->where('assigned', false)->get();

        foreach ($stud as $current_stud) {
            $current_stud->survey_data = json_decode($current_stud->survey_data);
        }


        foreach ($lehr as $current_lehr) {

            $current_lehr->survey_data = json_decode($current_lehr->survey_data);
            $current_lehr->matchings = [];

            $matchings = [];

            foreach ($stud as $current_stud) {

                if ($current_lehr->survey_data->schulart == $current_stud->survey_data->schulart) {
                    if (in_array($current_lehr->survey_data->landkreis,  $current_stud->survey_data->landkreise)) {
                        if ($current_lehr->survey_data->schulart == 'Grundschule') {
                            $current_stud->count_matchings += 1;
                            $current_stud->mse = $this->mse($current_lehr, $current_stud);
                            $matchings[] = $current_stud;
                        } elseif (array_intersect($current_lehr->survey_data->faecher, $current_stud->survey_data->faecher)) {
                            if (in_array($current_lehr->survey_data->landkreis,  $current_stud->survey_data->landkreise)) {
                                $current_stud->count_matchings += 1;
                                $current_stud->mse = $this->mse($current_lehr, $current_stud);
                                $matchings[] = $current_stud;
                            }
                        }
                    }
                }
            }

            usort($matchings, array('App\Http\Controllers\UserController', 'compareMatchings'));


            $current_lehr->matchings = $matchings;

            $current_lehr->mses = [];
            $mses = [];
            if (count($matchings)) {
                foreach ($matchings as $matching) {
                    $mses[] = $matching->mse;
                }
                $current_lehr->mses = $mses;
            }

            if (isset($current_lehr->survey_data->faecher))
                $current_lehr->survey_data->faecher = implode(', ', $current_lehr->survey_data->faecher);
        }
        // alle studenten matchen f??r die nur ein lehrer gefunden wurde
        $unmatchable_lehr = [];
        $matchings = [];
        foreach ($lehr as $key => $current_lehr) {
            // falls lehrkraft keine matchings
            if (count($current_lehr->matchings) == 0) {
                $unmatchable_lehr[] = $current_lehr;
                $lehr->forget($key);
            } else
                foreach ($current_lehr->matchings as $current_stud) {
                    if ($current_stud->count_matchings == 1) {
                        // f??r diesen student gibt es genau nur diesen lehrer als matching
                        // da bereits sortiert wird stud mit besserem mse mit der lehrkr??ft falls mehrere studenten nur mit dieser lehrkraft k??nnen

                        $matching = [
                            'lehr' => $current_lehr,
                            'stud' => $current_stud
                        ];
                        $matchings[] = $matching;
                        $lehr->forget($key);

                        break; // nachdem der bestm??glich student daraus zugewiesen wurde abbruch
                    }
                }
        }
        $assigned_matchings = DB::table('user_user')->get();
        $assigned = [];
        foreach($assigned_matchings as $am) {
            $assigned_lehr = User::where('role', 'lehr')->where('valid', true)->where('assigned', true)->where('id', $am->user_id)->get();
            $assigned_stud = User::where('role', 'stud')->where('valid', true)->where('assigned', true)->where('id', $am->matching_id)->get();
            $mse = $am->mse;
            $assigned[] = ['lehr' => $assigned_lehr[0], 'stud' => $assigned_stud[0], 'mse' => $am->mse];

        }
        // dd($assigned_matchings);

        return view('matchings', ['users' => $lehr, 'matchings' => $matchings, 'assigned_matchings' => $assigned, 'unmatchable_lehr' => $unmatchable_lehr]);
    }

    public function setAssigned(Request $request, $lehrid, $studid, $mse)
    {
        $lehr = User::where('role', 'lehr')->where('valid', true)->where('assigned', false)->where('id', $lehrid)->get();
        $stud = User::where('role', 'stud')->where('valid', true)->where('assigned', false)->where('id', $studid)->get();

        $lehr = $lehr[0];
        $stud = $stud[0];

        $lehr->assigned = true;
        $lehr->save();

        DB::insert('insert into user_user (user_id, matching_id, mse) values (?, ?, ?)', [$lehrid, $studid, $mse]);

        $stud->assigned = true;
        $stud->save();

        return redirect()->route('users.matchings');
    }

    public function setUnassigned(Request $request, $lehrid, $studid)
    {
        $lehr = User::where('role', 'lehr')->where('valid', true)->where('assigned', true)->where('id', $lehrid)->get();
        $stud = User::where('role', 'stud')->where('valid', true)->where('assigned', true)->where('id', $studid)->get();

        $lehr = $lehr[0];
        $stud = $stud[0];

        $lehr->assigned = false;
        $lehr->save();

        DB::table('user_user')->where('user_id', $lehrid)->where('matching_id', $studid)->delete();

        $stud->assigned = false;
        $stud->save();

        return redirect()->route('users.matchings');
    }

    public function confirmMatching(Request $request)
    {
        $assigned_matchings = DB::table('user_user')->get();
        $assigned = [];
        foreach($assigned_matchings as $am) {
            $assigned_lehr = User::where('role', 'lehr')->where('valid', true)->where('assigned', true)->where('id', $am->user_id)->get();
            $assigned_stud = User::where('role', 'stud')->where('valid', true)->where('assigned', true)->where('id', $am->matching_id)->get();
            $assigned[] = ['lehr' => $assigned_lehr[0], 'stud' => $assigned_stud[0], 'mse' => $am->mse];

        }
    }
}
