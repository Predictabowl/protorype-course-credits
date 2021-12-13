<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tabella Integrazione ({{ $front->user->name }})
        </h2>
    </x-slot>

    <x-mainpanel width="max-w-full">
        <x-panel>
            <div class="flex justify-start mb-4">
                <div>
                    <a href="{{route("studyPlanPdf",[$front])}}">
                        <x-button-icon width="w-14" height="h-14" name="id">
                            <img src="/images/print-icon.svg" alt="Stampa">
                        </x-button-icon>
                    </a>
                    {{ __("Download PDF") }}
                </div>
            </div>
            <div class="place-content-center">
                <table class="table-auto w-full border-4 border-black">
                    <thead class="w-full bg-gray-100">
                        <tr>
                            <th/>
                            <th class="p-1">SSD</th>
                            <th class="p-1">Nome Insegnamento</th>
                            <th class="p-1">Esami Riconosciuti</th>
                            <th class="p-1">CFU</th>
                            <th class="p-1">Anno</th>
                            <th class="p-1">Mod.</th>
                            <th class="p-1">CFU a Debito</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studyPlan->getexamBlocks() as $examBlock)
                            <tr class = "border-b-4 border-black"/>
                            <?php $startBlock = true; ?>
                            @foreach($examBlock->getExamOptions() as $option)
                                <?php
                                    $bOptionCleared  = $option->getRecognizedCredits() == $examBlock->getCfu();
                                    $bOptionPartial = $option->getRecognizedCredits() > 0 && !$bOptionCleared;
                                    $optionColorClass = "";
                                    if ($bOptionCleared){
                                        $optionColorClass = "text-green-600";
                                    }
                                    if ($bOptionPartial){
                                        $optionColorClass = "text-red-600";
                                    }
                                ?>
                                <tr class="border-l border-r {{ !$startBlock ? "border-t" : ""}} border-gray-400">
                               {{--  <tr class="border border-gray-400">  --}}
                                    <td class="text-xs" x-data="{ open: false }">
                                        @if($option->getCompatibleOptions()->count() > 0)
                                            <button x-on:click="open = !open" x-show="!open"
                                                class="rounded-full w-7 items-center bg-blue-100 hover:bg-blue-400 text-lg">
                                                +
                                            </button>
                                            <div x-on:click="open = !open" x-show="open"
                                                class="cursor-pointer bg-blue-100 hover:bg-blue-400">
                                                @foreach($option->getCompatibleOptions() as $compatibleOption)
                                                    {{$compatibleOption}}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="border-r border-gray-400">
                                        {{ html_entity_decode(str_replace("-","&#8209;",$option->getSsd())) }}
                                    </td>
                                    <td>
                                        {{ $option->getExamName() }}
                                        <span class="{{$optionColorClass}}">
                                            {{ $bOptionCleared ? '[Esame Riconosciuto]' : ''}}
                                            {{ $bOptionPartial ? '[Dovuta Integarzione]' : ''}}
                                        </span>
                                    </td>
                                    <td class="text-sm">
                                        <ul class="list-disc">
                                        @foreach($option->getTakenExams() as $taken)
                                            <li>
                                                {{ $taken->getExamName() }}({{ $taken->getSsd() }}): 
                                                {{ $taken->getActualCfu()}}/{{$taken->getCfu()}}
                                            </li>
                                        @endforeach
                                        </ul>
                                    </td>
                                    <td class="text-center {{$optionColorClass}}">
                                        {{ $option->getRecognizedCredits() }}
                                    </td>
                                    <td class="text-center">
                                        {!!$option->getCourseYear() != null ? $option->getCourseYear().'°' : '' !!}
                                    </td>
                                    
                                    @if($startBlock)
                                        <?php $rows = $examBlock->getExamOptions()->count() ?>
                                        <td class="text-center border-l border-gray-400" 
                                            rowspan="{{ $rows }}">
                                                <div class="m-auto" style="max-width: 120px;">
                                                    @if($examBlock->getExamOptions()->count()  == 1)
                                                        {{ $examBlock->getCfu() }} cfu, Obbligatorio
                                                    @else
                                                        <?php $numOptions = $examBlock->getNumExams() ?>
                                                            {{ $numOptions }} esam{{ $numOptions == 1 ? "e" : "i"}} da 
                                                            {{ $examBlock->getCfu() }} cfu a scelta.
                                                    @endif
                                                </div>
                                        </td>
                                        <?php $integration = $option->getBlock()->getIntegrationValue() ?>
                                        <td class="text-center font-semibold text-lg border-l border-gray-400 
                                            {{$integration == 0 ? 'text-green-600' : 'text-red-600'}}" 
                                            rowspan="{{ $rows }}">
                                                {{ $option->getBlock()->getIntegrationValue()}}
                                        </td>
                                    @endif
                                   <?php $startBlock = false; ?>
                               </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-panel>
        <x-panel>
            <div class="sm:flex justify-around">
                <div>
                @if($studyPlan->getLeftoverExams()->count() > 0)
                    <span class="text-lg"> Lista esami con crediti inutilizzati: </span>
                    <ul class="list-disc pl-6">
                        @foreach($studyPlan->getLeftoverExams() as $exam)
                            <li>
                                [{{ $exam->getSsd()}}] {{ $exam->getExamName() }}: {{ $exam->getActualCfu() }}/{{ $exam->getCfu()}}
                            </li>
                        @endforeach
                    </ul>
                @endif
                </div>
                <div class="text-xl">
                    <?php $cfu = $studyPlan->getRecognizedCredits() ?>
                    <p>Totale CFU riconosciuti: <span class="font-bold text-green-600">{{ $cfu }} </span></p>
                    <p>CFU da sostenere: <span class="font-bold text-red-600">{{ $front->course->cfu - $cfu }}</span></p>
                    <div class="pl-4 text-base"> di cui:
                        <ul class="list-disc pl-6">
                            <?php $activities = $front->course->otherActivitiesCfu ?>
                            @if(isset($activities))
                                <li>Altre attività: {{ $activities }} cfu</li>
                            @endif
                            <li>Prova Finale: {{ $front->course->finalExamCfu}} cfu</li>
                        </ul>
                    </div>

                </div>
            </div>
            <div class="text-center text-xl">
                <p>Anno di Corso: {{ $courseYear }}°</p>
                <p>
                    Coorte: {{ $academicYear - $courseYear +1 }}
                </p>
            </div>
        </x-panel>
    </x-mainpanel>
</x-app-layout>
