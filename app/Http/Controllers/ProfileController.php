<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;

use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;


class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $user->survey_data = json_decode($user->survey_data);
        if (isset($user->survey_data->faecher))
            $user->survey_data->faecher = implode(', ', $user->survey_data->faecher);
        if (isset($user->survey_data->landkreise))
            $user->survey_data->landkreise = implode(', ', $user->survey_data->landkreise);
        if (isset($user->survey_data->verkehrsmittel))
            $user->survey_data->verkehrsmittel = implode(', ', $user->survey_data->verkehrsmittel);
        if (isset($user->survey_data->praktika))
            $user->survey_data->praktika = implode(', ', $user->survey_data->praktika);
        if (isset($user->survey_data->freue_auf))
            $user->survey_data->freue_auf = implode(', ', $user->survey_data->freue_auf);
        if (!$user->survey_data) {
            $this->edit();
        } else {
            return view('profile.details', compact('user'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = auth()->user();

        if ($user->survey_data) {
            if (strcasecmp($user->role, 'lehr') == 0 || strcasecmp($user->role, 'stud') == 0) {
                $attention = 'Die Bewerbung f??r den aktuellen Jahrgang 2022/2023 liegt uns vor. Sie k??nnen Angaben korrigieren, w??hrend Sie das Formular durchgehen. Bitte beachten Sie, dass Pflichtfelder weiterhin ausgef??llt sein und ??nderungen anschlie??end best??tigt werden m??ssen, bevor diese wirksam werden k??nnen.';
                return view('surveys.' . lcfirst($user->role), ['attention' => $attention, 'user' => $user]);
            } else {
                return view('surveys.admin_mod', ['attention' => 'Hier k??nnen Sie Ihre Daten korrigieren.', 'user' => $user]);
            }
        } else {
            if (strcasecmp($user->role, 'lehr') == 0 || strcasecmp($user->role, 'stud') == 0) {
                $attention = 'Zum aktuellen Jahrgang 2022/2023 liegt uns keine Bewerbung vor. Wir bitten Sie, sich kurz Zeit zu nehmen und das Bewerbungsformular auszuf??llen.';
                return view('surveys.' . lcfirst($user->role), ['attention' => $attention]);
            } else {
                return view('surveys.admin_mod', ['attention' => 'Bitte vervollst??ndigen Sie die Daten.', 'user' => $user]);
            }
        }
    }

    public function account()
    {
        return view('profile.account', ['user' => auth()->user()]);
    }

    public function update(Request $request, $id)
    {
        $id = auth()->id();
        $user = User::find($id);

        $this->validate($request, [
            'email' => 'email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
        ]);

        $input = $request->all();

        if ($input['email'] != $user->email) {
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
        }

        if (isset($input['password'])) {
            $this->validate($request, [
                'password' => [
                    Password::min(10)
                        ->numbers()
                        ->symbols()
                        ->mixedCase()
                        ->letters(),
                ]
            ]);
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user->update($input);

        session()->flash('success', 'true');

        return redirect()->route('profile.account');
    }

    public function matchings()
    {
        $user = auth()->user();
        // dd($user);
        // $matchings = [];
        // if(true) { // soll true sein wenn email versendet
        //     $matchings = $user->matchings;
        // }
        
        foreach ($user->matchings as $m) {
            $m->survey_data = json_decode($m->survey_data);
            if (isset($m->survey_data->faecher))
                $m->survey_data->faecher = implode(', ', $m->survey_data->faecher);
            if (isset($m->survey_data->landkreise))
                $m->survey_data->landkreise = implode(', ', $m->survey_data->landkreise);
            if (isset($m->survey_data->verkehrsmittel))
                $m->survey_data->verkehrsmittel = implode(', ', $m->survey_data->verkehrsmittel);
        }
        // dd($user);
        
        // if (lcfirst($user->role) == 'lehr') {
        //     foreach ($user->matchings as $m) {
        //         $m->survey_data = json_decode($m->survey_data);
        //         if (isset($m->survey_data->faecher))
        //             $m->survey_data->faecher = implode(', ', $m->survey_data->faecher);
        //         if (isset($m->survey_data->landkreise))
        //             $m->survey_data->landkreise = implode(', ', $m->survey_data->landkreise);
        //         if (isset($m->survey_data->verkehrsmittel))
        //             $m->survey_data->verkehrsmittel = implode(', ', $m->survey_data->verkehrsmittel);
        //     }
        // } elseif(lcfirst($user->role) == 'stud') {
        //     $matching = DB::table('user_user')->where('matching_id', $user->id)->get();
        //     $matching_id = $matching[0]->user_id;
        //     $matching_mse = $matching[0]->mse;
        //     $matching = User::where('id', $matching_id)->get();
        //     // dd($user);
        //     $user->matchings = $matching;
        //     // dd($user);
        //     foreach ($user->matchings as $m) {
        //         // dd($m);
        //         $m->survey_data = json_decode($m->survey_data);
        //         if (isset($m->survey_data->faecher))
        //             $m->survey_data->faecher = implode(', ', $m->survey_data->faecher);
        //         if (isset($m->survey_data->landkreise))
        //             $m->survey_data->landkreise = implode(', ', $m->survey_data->landkreise);
        //         if (isset($m->survey_data->verkehrsmittel))
        //             $m->survey_data->verkehrsmittel = implode(', ', $m->survey_data->verkehrsmittel);
        //     }
        // }

        // dd($user);
        // dd($matchings);
        return view('profile.matchings', compact('user'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $id = auth()->id();
    //     $user = User::find($id);

    //     $this->validate($request, [
    //         'vorname' => 'filled|max:255',
    //         'nachname' => 'filled|max:255',
    //         'email' => 'email|unique:users,email,' . $id,
    //         'password' => 'same:confirm-password',
    //     ]);

    //     $input = $request->all();

    //     if($input['email'] != $user->email) {
    //         $user->email_verified_at = null;
    //         $user->sendEmailVerificationNotification();
    //     }

    //     if (isset($input['password'])) {
    //         $this->validate($request, [
    //             'password' => [Password::min(10)
    // 			->numbers()
    // 			->symbols()
    // 			->mixedCase()
    // 			->letters(),
    // 		    ]
    //         ]);
    //         $input['password'] = Hash::make($input['password']);
    //     } else {
    //         $input = Arr::except($input, array('password'));
    //     }

    //     $user->update($input);

    //     session()->flash('success', 'true');

    //     return redirect()->route('profile.edit');
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
