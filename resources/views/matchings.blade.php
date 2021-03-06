@extends ('layouts.app')

@section('content')

<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

<div class="flex flex-row h-full mx-0 sm:mx-5 mt-1 sm:mt-10 mb-1 sm:mb-10">

    <!-- Nav -->

    @include('layouts.navigation')

    <!-- Nav -->

    <!-- Content -->

    <div class="px-1 md:px-8 py-1 md:py-8 text-gray-700 w-screen sm:rounded-r-lg bg-gray-900">

        <div class="mx-auto rounded">

            <!-- Übernommene Vorschläge -->

            <h1 class="font-semibold text-2xl text-gray-200">

                Paarungen

            </h1>

            <div class="mt-1 mb-6 text-sm text-gray-300 grid text-center sm:text-left flex">

                <p>Hier erhalten Sie eine Übersicht der möglichen Paarungen. Die Berechnung erfolgt auf Basis des <em>Mean Square Errors (Mittlerer quadratischer Fehler)</em> - kurz <em>MSE</em> - auf einer Skala von 0-10. Umso kleiner dieser ist, umso geringer ist die Abweichung beziehungsweise besser die Paarung. <strong>Ein großer MSE ist demnach nicht ratsam.</strong> Zu jeder Lehrkraft werden Student*innen aufgelistet, die bezüglich Schulart, Landkreis und mindestens einem Fach kompatibel sind. Sollten Sie einen Vorschlag in die Liste aufnehmen, werden beide Partner*innen aus dem Pool der Suchenden entfernt.</p>

            </div>

            <div class="bg-gray-800 px-1 md:px-8 py-1 md:py-8 rounded-md">

                <div class="grid justify-items-center sm:justify-items-start select-none">

                    <h2 class="font-semibold text-lg text-gray-200">

                        Übernommene Vorschläge

                    </h2>

                    <div class="mt-1 text-sm text-gray-300 grid text-center sm:text-left flex">

                        @if (count($assigned_matchings) == 0)

                        @elseif (count($assigned_matchings) == 1)

                            <p>Der folgende Vorschlag wurden von Ihnen übernommen.</p>

                        @elseif (count($assigned_matchings) > 1)

                            <p>Die folgenden <strong>{{ count($assigned_matchings) }} Vorschläge</strong> wurden von Ihnen übernommen.</p>

                        @endif

                    </div>

                </div>

                <table class="min-w-full mt-4 mb-2 mr-4 shadow-sm rounded-lg">

                    <tbody>

                        @if (count($assigned_matchings) == 0)

                            <p class="px-6 py-3 bg-gray-700 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider mt-4 rounded-md">Bisher wurden keine Vorschläge von Ihnen übernommen. Suchen Sie auf Basis des MSE nach Paarungen.</p>

                        @elseif (count($assigned_matchings) > 0)

                            <tr>

                                <th class="hidden sm:table-cell px-6 py-3 border-b border-gray-200 bg-gray-700 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider rounded-tl-md font-bold">
                                    #</th>

                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-700 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider font-bold">
                                    Lehrkraft</th>

                                <th class="hidden sm:table-cell px-6 py-3 border-b border-gray-200 bg-gray-700 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider font-bold">
                                    Student*in</th>

                                <th class="hidden sm:table-cell px-6 py-3 border-b border-gray-200 bg-gray-700 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider font-bold">
                                    MSE</th>

                                <th class="hidden sm:table-cell px-6 py-3 border-b border-gray-200 bg-gray-700 text-right text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider rounded-tr-md font-bold">
                                </th>

                            </tr>

                            @foreach($assigned_matchings as $index => $matching)

                            <tr class="border-t border-gray-200 bg-gray-700">

                                <td class="hidden sm:table-cell text-sm pl-6 py-4 whitespace-no-wrap text-gray-100">

                                    {{ $index + 1 }}

                                </td>

                                <td class="px-6 py-4 whitespace-no-wrap">

                                    <div class="text-xs sm:text-sm leading-5 font-medium text-white">{{ $matching['lehr']->vorname }} {{ $matching['lehr']->nachname }}</div>

                                    <a href="mailto:{{  $matching['lehr']->email }}" class="text-xs sm:text-sm leading-5 text-gray-400 hover:text-gray-100 break-words">{{ $matching['lehr']->email }}</a>

                                </td>

                                <td class="px-6 py-4 whitespace-no-wrap">

                                    <div class="text-xs sm:text-sm leading-5 font-medium text-white">{{ $matching['stud']->vorname }} {{ $matching['stud']->nachname }}</div>

                                    <a href="mailto:{{  $matching['lehr']->email }}" class="text-xs sm:text-sm leading-5 text-gray-400 hover:text-gray-100 break-words">{{ $matching['stud']->email }}</a>

                                </td>

                                <td class="px-6 py-4 whitespace-no-wrap">

                                    <div class="text-sm leading-5 font-normal text-white select-none font-bold bg-gray-500 text-center p-1 w-12 rounded-sm">
                
                                        {{ $matching['mse'] }}
                                        
                                    </div>

                                </td>
                                
                                <!-- Löschen -->

                                <td class="hidden sm:table-cell px-6 py-4 whitespace-no-wrap float-right text-sm leading-5 font-medium">

                                    <form action="{{ route('matchings.setunassigned', ['lehr' => $matching['lehr']->id, 'stud' => $matching['stud']->id]) }}" method="get">

                                        @csrf

                                        <button type="submit" class="py-2 px-2 rounded-full text-white text-sm flex focus:outline-none bg-yellow-700 has-tooltip hover:bg-yellow-900 border-2 border-white transition ease-in-out duration-150">

                                            <div class="grid justify-items-center">

                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                
                                                </svg>

                                                <span class='tooltip rounded p-1 px-2 bg-gray-900 text-white -mt-10 text-xs transition ease-in-out duration-150'>Entfernen</span>

                                            </div>

                                        </button>

                                    </form>


                                </td>

                                <!-- Löschen -->

                            </tr>

                            @endforeach

                        @endif

                    </tbody>

                </table>

            </div>

            <!-- Alternativlose Vorschläge -->

            <div class="bg-gray-800 px-1 md:px-8 py-1 md:py-8 rounded-md mt-4">

                <div class="grid justify-items-center sm:justify-items-start select-none">

                    <h2 class="font-semibold text-lg text-gray-200">

                        Alternativlose Vorschläge

                    </h2>

                    <div class="mt-1 text-sm text-gray-300 grid text-center sm:text-left flex">

                        @if (count($matchings) == 0)

                        @elseif (count($matchings) == 1)

                            <p>Der folgende Vorschlag ist derzeit alternativlos.</p>

                        @elseif (count($matchings) > 1)

                            <p>Die folgenden <strong>{{ count($matchings) }} Vorschläge</strong> sind derzeit alternativlos. Es ist daher ratsam, diese Paarungen durchzuführen, um alle Bewerber*innen zuzuweisen. Übernehmen Sie mittels einer Aktion einen Vorschlag.</p>

                        @endif

                    </div>

                </div>

                <table class="min-w-full mt-4 mb-2 mr-4 shadow-sm rounded-lg">

                    <tbody>

                        @if (count($matchings) == 0)

                            <p class="px-6 py-3 bg-gray-700 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider mt-4 rounded-md">Keine alternativlosen Paarungen vorhanden.</p>

                        @elseif (count($matchings) > 0)

                            <tr>

                                <th class="hidden sm:table-cell px-6 py-3 border-b border-gray-200 bg-gray-700 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider rounded-tl-md font-bold">
                                    #</th>

                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-700 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider font-bold">
                                    Lehrkraft</th>

                                <th class="hidden sm:table-cell px-6 py-3 border-b border-gray-200 bg-gray-700 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider font-bold">
                                    Student*in</th>

                                <th class="hidden sm:table-cell px-6 py-3 border-b border-gray-200 bg-gray-700 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider font-bold">
                                    MSE</th>

                                <th class="hidden sm:table-cell px-6 py-3 border-b border-gray-200 bg-gray-700 text-right text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider rounded-tr-md font-bold">
                                </th>

                            </tr>

                            @foreach($matchings as $index => $matching)

                            <tr class="border-t border-gray-200 bg-gray-700">

                                <td class="hidden sm:table-cell text-sm pl-6 py-4 whitespace-no-wrap text-gray-100">

                                    {{ $index + 1 }}

                                </td>

                                <td class="px-6 py-4 whitespace-no-wrap">

                                    <div class="text-xs sm:text-sm leading-5 font-medium text-white">{{ $matching['lehr']->vorname }} {{ $matching['lehr']->nachname }}</div>

                                    <a href="mailto:{{  $matching['lehr']->email }}" class="text-xs sm:text-sm leading-5 text-gray-400 hover:text-gray-100 break-words">{{ $matching['lehr']->email }}</a>

                                </td>

                                <td class="px-6 py-4 whitespace-no-wrap">

                                    <div class="text-xs sm:text-sm leading-5 font-medium text-white">{{ $matching['stud']->vorname }} {{ $matching['stud']->nachname }}</div>

                                    <a href="mailto:{{  $matching['lehr']->email }}" class="text-xs sm:text-sm leading-5 text-gray-400 hover:text-gray-100 break-words">{{ $matching['stud']->email }}</a>

                                </td>

                                <td class="px-6 py-4 whitespace-no-wrap">

                                    <div class="text-sm leading-5 font-normal text-white select-none font-bold bg-gray-500 text-center p-1 w-12 rounded-sm">
                
                                        {{ $matching['stud']->mse }}
                                        
                                    </div>

                                </td>
                                
                                <!-- Löschen -->

                                <td class="hidden sm:table-cell px-6 py-4 whitespace-no-wrap float-right text-sm leading-5 font-medium">

                                    <form action="{{ route('matchings.setassigned', ['lehr' => $matching['lehr']->id, 'stud' => $matching['stud']->id, 'mse' => $matching['stud']->mse]) }}" method="get">

                                        @csrf

                                        <button type="submit" class="py-2 px-2 rounded-full text-white text-sm flex focus:outline-none bg-green-700 has-tooltip hover:bg-green-900 border-2 border-white transition ease-in-out duration-150">

                                            <div class="grid justify-items-center">

                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                                
                                                </svg>

                                                <span class='tooltip rounded p-1 px-2 bg-gray-900 text-white -mt-10 text-xs transition ease-in-out duration-150'>Aufnehmen</span>

                                            </div>

                                        </button>

                                    </form>


                                </td>

                                <!-- Löschen -->

                            </tr>

                            @endforeach

                        @endif

                    </tbody>

                </table>

            </div>

            <!-- Alternativlose Vorschläge -->

            <!-- Weitere Vorschläge -->

            <div class="bg-gray-800 px-1 md:px-8 py-1 md:py-8 rounded-md mt-4">

                <div class="grid justify-items-center sm:justify-items-start select-none">

                    <h2 class="font-semibold text-lg text-gray-200">

                        Weitere Vorschläge

                    </h2>

                    <div class="mt-1 text-sm text-gray-300 grid text-center sm:text-left flex w-full">

                        @if ($users->count() == 0)

                            <p class="px-6 py-3 bg-gray-700 text-left text-xs leading-4 font-medium text-gray-400 uppercase tracking-wider mt-4 rounded-md">Keine weiteren Paarungen vorhanden.</p>

                        @elseif ($users->count() >= 1)

                            @if ($users->count() == 1)

                            <p>Folgende Paarung ist möglich.</p>

                            @elseif ($users->count() > 1)

                                <p>Die folgenden <strong>{{ $users->count() }} Paarungen</strong> sind derzeit möglich.</p>

                            @endif

                            <div class="min-w-full mt-4 mb-2 mr-4 shadow-sm">

                                @php

                                    $zaehler = 0;

                                @endphp

                                <div>

                                    <p class="px-6 py-3 border-b border-gray-200 bg-gray-700 text-left text-xs leading-4 font-medium text-gray-400 rounded-t-lg tracking-wider uppercase font-bold">Auflistung von Lehrkräften und kompatiblen Student*innen</p>

                                    @foreach($users as $index => $user)

                                        <div class="border-b border-gray-200 bg-gray-700 flex">

                                            <div class="hidden sm:table-cell text-sm pl-6 py-4 text-gray-100 w-12">

                                                {{ ++$zaehler }}

                                            </div>

                                            <div class="px-6 py-4 w-96">

                                                <div class="text-xs sm:text-sm leading-5 font-medium text-white w-64">

                                                    <a href="{{ route('profile.details', ['id' => $user->id]) }}" class="text-xs sm:text-sm leading-5 font-medium text-white hover:underline break-all">

                                                        {{ $user->survey_data->vorname }} {{ $user->survey_data->nachname }}

                                                    </a>

                                                </div>

                                                <a href="mailto:{{ $user->email }}" class="text-xs sm:text-sm leading-5 text-gray-400 hover:text-gray-100 break-all">{{ $user->email }} </a>

                                            </div>

                                            <div class="hidden sm:table-cell px-6 py-4 w-96">

                                                <div class="text-xs sm:text-sm leading-5 font-medium text-white">
                            
                                                    {{ $user->survey_data->schulart }}
                                                    
                                                </div>

                                                <!-- Fächer -->

                                                @if(isset($user->survey_data->faecher))

                                                    <div class="text-xs sm:text-sm leading-5 font-medium text-gray-400">
                                
                                                        {{ $user->survey_data->faecher }}
                                                        
                                                    </div>

                                                @else

                                                    <div class="text-xs sm:text-sm leading-5 font-medium text-gray-400">
                            
                                                        Keine Fächer angegeben
                                                        
                                                    </div>

                                                @endif

                                            </div>

                                            <div class="hidden sm:table-cell px-6 py-4 whitespace-no-wrap w-64">

                                                <div class="text-xs sm:text-sm leading-5 font-medium text-white">
                            
                                                    {{ $user->survey_data->postleitzahl }}
                                                    
                                                </div>

                                                <div class="text-xs sm:text-sm leading-5 font-medium text-gray-400">
                            
                                                    {{ $user->survey_data->ort }}
                                                    
                                                </div>

                                            </div>

                                            <div class="w-full px-6 py-4 flex">

                                                @foreach($user->matchings as $count=>$matching)

                                                    <!-- MSE -->

                                                    <div x-data="{ modelOpen: false }" class="flex flex-wrap mr-2 mb-2">

                                                        <button @click="modelOpen =!modelOpen" class="text-sm flex items-center justify-center px-3 py-2 space-x-2 text-white transition-colors duration-200 transform bg-indigo-500 rounded-md dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:bg-indigo-700 hover:bg-indigo-600 focus:outline-none focus:bg-indigo-500 focus:ring focus:ring-indigo-300 focus:ring-opacity-50 max-h-9">

                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                              <path d="M9 9a2 2 0 114 0 2 2 0 01-4 0z" />
                                                              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a4 4 0 00-3.446 6.032l-2.261 2.26a1 1 0 101.414 1.415l2.261-2.261A4 4 0 1011 5z" clip-rule="evenodd" />
                                                            </svg>

                                                            <span>{{ $matching->survey_data->vorname }} {{ $matching->survey_data->nachname }} ({{ $user->mses[$count] }})</span>

                                                        </button>

                                                        <!-- ModelOpen -->

                                                        <div x-show="modelOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">

                                                            <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">

                                                                <div x-cloak @click="modelOpen = false" x-show="modelOpen" 
                                                                    x-transition:enter="transition ease-out duration-300 transform"
                                                                    x-transition:enter-start="opacity-0" 
                                                                    x-transition:enter-end="opacity-100"
                                                                    x-transition:leave="transition ease-in duration-200 transform"
                                                                    x-transition:leave-start="opacity-100" 
                                                                    x-transition:leave-end="opacity-0"
                                                                    class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-40" aria-hidden="true"
                                                                ></div>

                                                                <!-- ModelOpen -->

                                                                <!-- ModelOpen x-cloak -->

                                                                <div x-cloak x-show="modelOpen" 
                                                                    x-transition:enter="transition ease-out duration-300 transform"
                                                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                                    x-transition:leave="transition ease-in duration-200 transform"
                                                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                                                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                                    class="bg-gray-700 inline-block w-full max-w-xl p-8 my-20 overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl 2xl:max-w-2xl"
                                                                >

                                                                <!-- ModelOpen x-cloak -->

                                                                    <!-- ModelInner -->

                                                                    <div class="flex items-center justify-between space-x-4">

                                                                        <h1 class="text-xl font-medium text-gray-100">Vorschlag übernehmen</h1>

                                                                        <button @click="modelOpen = false" class="text-gray-400 focus:outline-none hover:text-gray-100">

                                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">

                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />

                                                                            </svg>

                                                                        </button>

                                                                    </div>

                                                                    <p class="mt-2">

                                                                        @if ($matching->count_matchings == 1) 

                                                                            <p class="text-gray-400 text-sm">Kann <strong>nur mit dieser Lehrkraft</strong> gematcht werden

                                                                        @else 

                                                                            <p class="text-gray-400 text-sm">Kann mit {{ $matching->count_matchings }} Lehrkräften gematcht werden.

                                                                        @endif

                                                                        Führen Sie gegebenenfalls aufgrund der Übereinstimmungen eine Paarung durch.</p> 

                                                                    </p>

                                                                    <h3 class="text-xs font-medium text-white uppercase mt-4">Student*in</h3>

                                                                    <div class="w-96 mt-2">

                                                                        <div class="text-xs sm:text-sm leading-5 font-medium text-white w-64 text-gray-400">

                                                                            <p>{{ $matching->survey_data->vorname }} {{ $matching->survey_data->nachname }}</p>
                                                                            <a href="mailto:{{ $matching->email }}" class="text-gray-400 hover:text-gray-100 break-words">{{ $matching->email }}</a>

                                                                        </div>

                                                                    </div>
                                                                        
                                                                    <div class="mt-4">

                                                                        <h3 class="text-xs font-medium text-white uppercase">Mean Square Error (MSE)</h3>

                                                                        <div class="pb-2 w-96 mt-2">

                                                                            <div class="text-xs sm:text-sm leading-5 font-medium text-white w-64 text-gray-400">

                                                                                    {{ $user->mses[$count] }}

                                                                            </div>

                                                                        </div>

                                                                        <div>

                                                                            <h3 class="text-xs font-medium text-white uppercase">Attribute zur Berechnung des MSE</h3>

                                                                            <div class="pb-2 w-96 text-sm text-gray-400 mt-2">

                                                                                <p>

                                                                                    Feedback Lehrkraft zu Student*in [Abweichung 0 bis 5]: {{ abs($user->survey_data->feedback_an - $matching->survey_data->feedback_von) }}

                                                                                </p>

                                                                                <p>

                                                                                    Feedback Student*in zu Lehrkraft [Abweichung 0 bis 5]: {{ abs($user->survey_data->feedback_von - $matching->survey_data->feedback_an) }}

                                                                                </p>

                                                                                <p>

                                                                                    Eigenstaendigkeit [Abweichung 0 bis 5]**: {{ abs($user->survey_data->eigenstaendigkeit - $matching->survey_data->eigenstaendigkeit) }}

                                                                                </p>

                                                                                <p>

                                                                                    Improvisation [Abweichung 0 bis 5]: {{ abs($user->survey_data->improvisation - $matching->survey_data->improvisation) }}

                                                                                </p>

                                                                                <p>

                                                                                    Freiraum [Abweichung 0 bis 3]: {{ abs($user->survey_data->freiraum - $matching->survey_data->freiraum) }}

                                                                                </p>

                                                                                <p>

                                                                                    Innovationsoffenheit [Abweichung 0 bis 5]: {{ abs($user->survey_data->innovationsoffenheit - $matching->survey_data->innovationsoffenheit) }}

                                                                                </p>

                                                                                <p>

                                                                                    Belastbarkeit [Abweichung 0 bis 5]:** {{ abs($user->survey_data->belastbarkeit - $matching->survey_data->belastbarkeit) }}

                                                                                </p>

                                                                            </div>

                                                                        </div>

                                                                        <p class="text-gray-400 text-xs mt-2">Umso kleiner der Wert, umso geringer die Abweichung. Attribute mit ** fließen stärker in die Gewichtung mit ein.</p>

                                                                    </div>

                                                                    <form action="{{ route('matchings.setassigned', ['lehr' => $user->id, 'stud' => $matching->id, 'mse' => $user->mses[$count]]) }}" method="get">

                                                                        @csrf
                                                                    
                                                                        <div class="flex justify-end mt-6">

                                                                            <button type="submit" class="border-2 border-white px-3 py-2 text-sm tracking-wide text-white capitalize transition-colors duration-200 transform bg-green-700 rounded-md hover:bg-green-900 focus:outline-none focus:bg-indigo-500 focus:ring focus:ring-indigo-300 focus:ring-opacity-50">
                                                                                Aufnehmen
                                                                            </button>

                                                                        </div>

                                                                    </form>

                                                                    <!-- ModelInner -->

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>
                                                    
                                                    <!-- MSE -->

                                                @endforeach

                                            </div>

                                        </div>

                                        @endforeach

                                    </div>

                                </div>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

            <!-- Weitere Vorschläge -->

        </div>

    </div>

</div>

<!-- Content -->

@endsection