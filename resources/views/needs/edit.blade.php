@extends ('layouts.app')

@section('content')

<div class="flex flex-row h-full mx-0 sm:mx-5 mt-1 sm:mt-10 mb-1 sm:mb-10">

    <!-- Nav -->

    @include('layouts.navigation')

    <!-- Nav -->

    <!-- Content -->

    <div class="px-1 md:px-8 py-1 md:py-8 text-gray-700 w-screen sm:rounded-r-lg bg-gray-600">

        <div class="mx-auto rounded">

            <!-- Success Message -->

            <script>
                function removemessage() {
                    document.getElementById('success_make_need').remove();
                }
            </script>

            @if ($message = Session::get('success'))

            <div class="text-white px-6 py-4 mx-4 border-0 rounded relative mb-4 bg-green-600" id="success_make_need">

                <span class="text-xl inline-block mr-2 align-middle">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">

                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />

                    </svg>

                </span>

                <span class="inline-block align-middle">

                    <b>Aktion erfolgreich ausgeführt.</b>

                </span>

                <button class="absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-4 mr-6 outline-none focus:outline-none" onclick="removemessage()">

                    <span>×</span>

                </button>

            </div>

            @endif

            <!-- Success Message -->

            <!-- Tabs -->

            <ul id="tabs" class="inline-flex w-full">

                <li class="px-4 py-2 font-medium text-sm text-gray-800 rounded-t opacity-50 bg-white border-gray-400"><a href="{{ route('needs.all') }}">Alle Bedarfe</a></li>

                <li class="px-4 py-2 -mb-px font-medium text-xs sm:text-sm text-gray-800 border-b-2 border-gray-700 rounded-t opacity-50 bg-white border-b-4 -mb-px opacity-100"><a href="{{ route('needs.user') }}">Meine Bedarfe</a></li>

                <li class="px-4 py-2 font-medium text-sm text-gray-800 rounded-t opacity-50 bg-white border-gray-400"><a href="{{ route('needs.make') }}">Bedarf erstellen</a></li>

            </ul>

            <!-- Tabs -->

            <!-- Tab Contents -->

            <div id="tab-contents">

                <!-- Bedarf erstellen -->

                <div id="third">

                    <div class="bg-white overflow-hidden rounded-b-md mb-5">

                        <div class="px-2 py-5 sm:px-4">

                            <div class="grid grid-cols-1 text-sm text-gray-500 text-light py-1 my-1">

                                <p class="font-medium text-gray-800 leading-none text-lg leading-6">Bedarfsänderung</p>

                                <p class="text-sm text-gray-500 mt-1 mb-3 mt-2">Hier haben Sie die Möglichkeit, die Angaben zu Ihrem Bedarf zu ändern.</p>

                            </div>

                            <form action="{{ route('needs') }}" method="post" class="mb-4">

                                @csrf

                                <input name="need_id" id="need_id" type="hidden" value={{ $need->id }}>

                                <div class="mt-1">

                                    <label for="body" class="sr-only">Body</label><textarea name="body" id="body" cols="30" rows="4" class="py-2 px-3 bg-gray-100 border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:border-transparent w-full rounded-lg @error('body') border-red-500 @enderror" placeholder="Beschreiben Sie Ihren Bedarf.">{{ $need->body }}</textarea>

                                    @error('body')

                                    <div class="text-red-500 mt-2 text-sm">

                                        {{ 'Ändern Sie Ihre Beschreibung des Hilfsgesuchs.' }}

                                    </div>

                                    @enderror

                                </div>

                                <!-- Betreuungsrahmen -->

                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">

                                    <div class="grid grid-cols-1 text-sm text-gray-500 text-light mt-3">

                                        <p class="font-medium text-gray-800 leading-none">Betreuungsrahmen</p>

                                        <p class="text-xs text-gray-500 mt-1 mb-3">Ändern Sie, wieviele Personen in Ihrer Klasse/Schule Betreuungsbedarf benötigen.</p>

                                        <div>

                                            <label for="rahmen" class="sr-only flex items-center">rahmen</label>

                                            <select name="rahmen" id="rahmen" class="text-gray-500 text-xs py-1 rounded-sm border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:border-transparent @error('rahmen') border-red-500 @enderror">
                                                <option>1</option>
                                                <option>2</option>
                                                <option>3</option>
                                                <option>4</option>
                                                <option>5</option>
                                            </select>

                                            <script>
                                                var rahmen_select = document.getElementById("rahmen");
                                                rahmen_select.value = '{{ $need->rahmen}}';
                                            </script>

                                            @error('rahmen')

                                            <div class="text-red-500 mt-2 text-sm">

                                                {{ 'Bitte legen Sie fest, wieviele Personen Sie betreuen möchten.' }}

                                            </div>

                                            @enderror

                                        </div>

                                    </div>

                                    <!-- Betreuungsrahmen -->

                                    <!-- Schulart -->

                                    <div class="grid grid-cols-1 text-sm text-gray-500 text-light mt-3">

                                        <p class="font-medium text-gray-800 leading-none">Schulart</p>

                                        <p class="text-xs text-gray-500 mt-1 mb-3">Ändern Sie, welche Schule Sie bevorzugen.</p>

                                        <div>

                                            <label for="rahmen" class="sr-only flex items-center">schulart</label>

                                            <select name="schulart" id="schulart" class="text-gray-500 text-xs py-1 rounded-sm border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:border-transparent @error('schulart') border-red-500 @enderror">
                                                <option>Keine</option>
                                                <option>Grundschule</option>
                                                <option>Weitere</option>
                                            </select>

                                            <script>
                                                var schulart_select = document.getElementById("schulart");
                                                schulart_select.value = "{{ $need->schulart }}";
                                            </script>

                                            @error('schulart')

                                            <div class="text-red-500 mt-2 text-sm">

                                                {{ 'Bitte legen Sie fest, welche Schule Sie bevorzugen.' }}

                                            </div>

                                            @enderror

                                        </div>

                                    </div>

                                    <!-- Schulart -->

                                    <!-- Fremdsprachkenntnisse -->

                                    <div class="grid grid-cols-1 text-sm text-gray-500 text-light mt-3">

                                        <p class="font-medium text-gray-800 leading-none">Fremdsprachkenntnisse</p>

                                        <p class="text-xs text-gray-500 mt-1 mb-3">Ändern Sie Angaben zur Fremdsprache, die das Betreuungsverhältnis ergänzen könnte.</p>

                                        <div>

                                            <label for="sprachkenntnisse" class="sr-only flex items-center">Sprachkenntnisse</label>

                                            <select name="sprachkenntnisse" id="sprachkenntnisse" class="text-gray-500 text-xs py-1 px-1 rounded-sm border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:border-transparent @error('sprachkenntnisse') border-red-500 @enderror">
                                                @foreach ($languages as $language)
                                                <option>{{ $language->Sprache }}</option>
                                                @endforeach
                                            </select>

                                            <script>
                                                var sprachkenntnisse_select = document.getElementById("sprachkenntnisse");
                                                sprachkenntnisse_select.value = "{{ $need->sprachkenntnisse }}";
                                            </script>

                                            @error('sprachkenntnisse')

                                            <div class="text-red-500 mt-2 text-sm">

                                                {{ 'Bitte bestimmen Sie, welche Fremdsprache Ihr Betreuungsverhältnis ergänzt.' }}

                                            </div>

                                            @enderror

                                        </div>

                                    </div>

                                    <!-- Fremdsprachkenntnisse -->

                                    <!-- Interessen -->

                                    <script>
                                        var interessen = [];

                                        function addToSelection(interesse, checked) {
                                            console.log(interesse, checked);
                                            if (checked) {
                                                interessen.push(interesse);
                                            } else {
                                                let i = interessen.indexOf(interesse);
                                                interessen.splice(i, 1);
                                            }
                                            console.log(interessen);
                                            document.getElementById('interessen').value = interessen;
                                        }
                                    </script>

                                    <input name="interessen" id="interessen" type=hidden value="" />

                                    <div class="grid grid-cols-1 text-sm text-gray-500 text-light mt-3">

                                        <p class="font-medium text-gray-800 leading-none">Interessen</p>

                                        <p class="text-xs text-gray-500 mt-1 mb-3">Geben Sie Interessen an, die das Betreuungsverhältnis ergänzen könnten.</p>

                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <input class="form-check-input me-1" type="checkbox" value="Sport" onclick="addToSelection(this.value, this.checked)">
                                                Sport
                                            </li>
                                            <li class="list-group-item">
                                                <input class="form-check-input me-1" type="checkbox" value="Musik" onclick="addToSelection(this.value, this.checked)">
                                                Musik
                                            </li>
                                            <li class="list-group-item">
                                                <input class="form-check-input me-1" type="checkbox" value="Lesen" onclick="addToSelection(this.value, this.checked)">
                                                Lesen
                                            </li>
                                            <li class="list-group-item">
                                                <input class="form-check-input me-1" type="checkbox" value="Schreiben" onclick="addToSelection(this.value, this.checked)">
                                                Schreiben
                                            </li>
                                            <li class="list-group-item">
                                                <input class="form-check-input me-1" type="checkbox" value="Kochen" onclick="addToSelection(this.value, this.checked)">
                                                Kochen
                                            </li>
                                            <li class="list-group-item">
                                                <input class="form-check-input me-1" type="checkbox" value="Kunst" onclick="addToSelection(this.value, this.checked)">
                                                Kunst
                                            </li>
                                            <li class="list-group-item">
                                                <input class="form-check-input me-1" type="checkbox" value="Basteln" onclick="addToSelection(this.value, this.checked)">
                                                Basteln
                                            </li>
                                            <li class="list-group-item">
                                                <input class="form-check-input me-1" type="checkbox" value="Malen" onclick="addToSelection(this.value, this.checked)">
                                                Malen
                                            </li>
                                            <li class="list-group-item">
                                                <input class="form-check-input me-1" type="checkbox" value="Reisen" onclick="addToSelection(this.value, this.checked)">
                                                Reisen
                                            </li>
                                            <li class="list-group-item">
                                                <input class="form-check-input me-1" type="checkbox" value="Tiere" onclick="addToSelection(this.value, this.checked)">
                                                Tiere
                                            </li>
                                        </ul>

                                    </div>
                                    
                                </div>

                                <script>
                                    let checkboxes = document.getElementsByClassName("form-check-input me-1");
                                    for (let checkbox of checkboxes) {
                                        @foreach($interessen as $interesse)
                                        if (checkbox.value == '{{ $interesse }}')
                                            checkbox.click();
                                        @endforeach
                                    }
                                </script>

                                <!-- Interessen -->

                                <!-- Studiengang -->

                                <div class="grid grid-cols-1 text-sm text-gray-500 text-light mt-3">

                                    <p class="font-medium text-gray-800 leading-none">Studiengang</p>

                                    <p class="text-xs text-gray-500 mt-1 mb-3">Aktualisieren Sie den präferierten Studiengang.</p>

                                    <div>

                                        <label for="studiengang" class="sr-only flex items-center">studiengang</label>

                                        <select name="studiengang" id="studiengang" class="text-gray-500 text-xs py-1 px-1 rounded-sm border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:border-transparent @error('studiengang') border-red-500 @enderror">
                                            <option>-</option>
                                            <option>Hauptfach Deutsch als Zweit- und Fremdsprache (B.A.)</option>
                                            <option>Nebenfach Deutsch als Zweit- und Fremdsprache (B.A.)</option>
                                            <option>Grundschule (LA)</option>
                                            <option>Mittelschule (LA)</option>
                                            <option>Realschule (LA)</option>
                                            <option>Gymnasium (LA)</option>
                                            <option>Sonstiges</option>
                                        </select>

                                        <script>
                                            var studiengang_select = document.getElementById("studiengang");
                                            studiengang_select.value = "{{ $need->studiengang }}";
                                        </script>

                                        @error('studiengang')

                                        <div class="text-red-500 mt-2 text-sm">

                                            {{ 'Bitte wählen Sie einen Studiengang.' }}

                                        </div>

                                        @enderror

                                    </div>

                                </div>

                                <!-- Studiengang -->

                                <!-- Datum -->

                                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

                                <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">

                                <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>

                                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/plugins/monthSelect/style.css">

                                <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/plugins/monthSelect/index.js"></script>

                                <script src="https://npmcdn.com/flatpickr/dist/l10n/de.js"></script>

                                <div class="grid grid-cols-1 text-sm text-gray-500 text-light mt-3">

                                    <p class="font-medium text-gray-800 leading-none">Betreuungszeitraum</p>

                                    <p class="text-xs text-gray-500 mt-1 mb-3">Geben Sie Ihren Betreuungszeitraum an.</p>

                                    <label for="datum" class="sr-only flex items-center">Datum</label>

                                    <input class="date form-control text-gray-500 text-xs py-1 px-1 rounded-sm border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:border-transparent @error('sprachkenntnisse') border-red-500 @enderror" type="text" id="datum" name="datum">

                                    @error('datum')

                                    <div class="text-red-500 mt-2 text-sm">

                                        {{ 'Bitte wählen Sie ein Datum aus.' }}

                                    </div>

                                    @enderror

                                    <script type="text/javascript">
                                        flatpickr("#datum", {
                                            altInput: true,
                                            altFormat: "F Y", // was "j F, Y"
                                            dateFormat: "Y-m-d", // was "Y-F"
                                            theme: "dark",
                                            //minDate: "today",
                                            mode: "range",
                                            "locale": "de",
                                            defaultDate: ["{{ $need->datum_start}}", "{{ $need->datum_end}}"]
                                        });
                                    </script>

                                </div>

                                <!-- Datum -->

                                <!-- Fachsemester -->

                                <div class="grid grid-cols-1 text-sm text-gray-500 text-light mt-3">

                                    <p class="font-medium text-gray-800 leading-none">Fachsemester</p>

                                    <p class="text-xs text-gray-500 mt-1 mb-3">Ändern Sie, welches Fachsemester Ihr*e Helfer*in mindestens erreicht haben sollte.</p>

                                    <div>

                                        <label for="fachsemester" class="sr-only flex items-center">fachsemester</label>

                                        <select name="fachsemester" id="fachsemester" class="text-gray-500 text-xs py-1 rounded-sm border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:border-transparent @error('fachsemester') border-red-500 @enderror">
                                            @for ($i = 0; $i < 15; $i++) <option>{{ $i }}</option>
                                                @endfor
                                        </select>

                                        <script>
                                            var fachsemester_select = document.getElementById("fachsemester");
                                            fachsemester_select.value = '{{ $need->fachsemester}}';
                                        </script>

                                        @error('fachsemester')

                                        <div class="text-red-500 mt-2 text-sm">

                                            {{ 'Bitte wählen Sie ein Fachsemester aus.' }}

                                        </div>

                                        @enderror

                                    </div>

                                </div>

                                <!-- Fachsemester -->

                        </div>

                        <div class="flex justify-end md:gap-8 gap-4 pt-1 rounded-md text-sm">

                            <button class="flex items-center w-auto bg-gray-700 hover:bg-gray-900 rounded-lg font-medium text-white px-4 py-2">

                                <div>

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                    </svg>

                                </div>

                                <div class="pl-3">

                                    <p>Bedarf ändern</p>

                                </div>

                        </div>

                        </button>

                        </form>

                    </div>

                </div>

            </div>

            <!-- Bedarf erstellen -->

        </div>

        <!-- Tab Contents -->

    </div>

</div>

<!-- Content -->

</div>

@endsection