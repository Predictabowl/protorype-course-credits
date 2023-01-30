<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Course Data') }}
        </h2>
    </x-slot>
    <?php
    if (isset($course)) {
        $name = $course->name;
        $cfu = $course->cfu;
        $maxCfu = $course->maxRecognizedCfu;
        $otherAct = $course->otherActivitiesCfu;
        $finalCfu = $course->finalExamCfu;
        $numOfYear = $course->numberOfYears;
        $cfuThreshOld = $course->cfuTresholdForYear;
    } else {
        $name = old('name');
        $cfu = old('cfu');
        $maxCfu = old('maxRecognizedCfu');
        $otherAct = old('otherActivitiesCfu');
        $finalCfu = old('finalExamCfu');
        $numOfYear = old('numberOfYears');
        $cfuThreshOld = old('cfuTresholdForYear');
    }
    ?>

    <x-mainpanel>

        <x-panel>
            <div class="place-content-center">
                <form method="POST" action="{{ $action }}">
                    @csrf
                    @if (isset($course))
                        @method('PUT')
                    @endif
                    <header class="flex justify-center">
                        <h2 class="ml-3 text-lg">{{ __('Course Data') }}</h2>
                    </header>
                    <div class="mt-1">
                        <x-label-input type="text" class="w-full" name="name" :value="$name" required>
                            {{ __('Course Name') }}
                        </x-label-input>
                    </div>
                    <div class="mt-2">
                        <x-label-input type="number" name="cfu" value="{{ $cfu }}" size="4"
                            required>
                            CFU Totali
                        </x-label-input>
                    </div>
                    <div class="mt-2">
                        <x-label-input type="number" name="finalExamCfu" value="{{ $finalCfu }}" size="3"
                            required>
                            CFU Prova Finale
                        </x-label-input>
                    </div>
                    <div class="mt-2">
                        <x-label-input type="number" name="numberOfYears" value="{{ $numOfYear }}" size="2"
                            required>
                            Numero di anni di corso previsti
                        </x-label-input>
                    </div>
                    <div class="mt-2">
                        <x-label-input type="number" name="maxRecognizedCfu" value="{{ $maxCfu }}"
                            size="4">
                            Massimo numero di CFU riconoscibili (Opzionale)
                        </x-label-input>
                    </div>
                    <div class="mt-2">
                        <x-label-input type="number" name="otherActivitiesCfu" value="{{ $otherAct }}"
                            size="3">
                            Numero CFU dovuti ad altre attività (Opzionale)
                        </x-label-input>
                    </div>
                    <div class="mt-2">
                        <x-label-input type="number" name="cfuTresholdForYear" value="{{ $cfuThreshOld }}"
                            size="3">
                            <span>
                                Numero di CFU richiesti per il riconoscimento di un anno
                                accademico<span class="align-super text-xs">1</span>
                        </x-label-input>
                    </div>
                    <ul class="mt-5 ml-3 text-xs list-decimal">
                        <li>Valore utilizzato per il calcolo della coorte,
                        se posto a 0 o meno lo studente richiedente viene sempre immatricolato al 1° anno.</li>
                    </ul>
                    <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                        <x-button>
                            {{ __('Ok') }}
                        </x-button>
                        <x-flash />
                    </div>
                </form>
            </div>
        </x-panel>
    </x-mainpanel>
</x-app-layout>
