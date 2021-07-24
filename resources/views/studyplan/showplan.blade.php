<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Integrazione') }}
        </h2>
    </x-slot>

    <x-mainpanel width="max-w-full">
        <x-panel>
            <h1>Crediti Assegnati</h1>
            <div class="place-content-center">
                <table class="table-auto w-full">
                    <thead class="w-full bg-gray-100">
                        <tr>
                            <th/>
                            <th class="p-1">SSD</th>
                            <th class="p-1">Nome Insegnamento</th>
                            <th class="p-1">Esami Riconosciuti</th>
                            <th class="p-1">CFU</th>
                            <th class="p-1">Mod.</th>
                            <th class="p-1">Integrazione</th>
                        </tr>
                    </thead>
                    <tbody class="" >
                        @foreach($studyPlan->getexamBlocks() as $examBlock)
                           <tr class = "border-t-4 border-black" x-data="{ blockstart: 1 }"/>
                            <?php $startBlock = true; ?>
                            @foreach($examBlock->getExamOptions() as $option)
                                <tr class="border-l border-r {{ !$startBlock ? "border-t" : ""}} border-gray-400">
                                    <td class="text-xs" x-data="{ open: false }">
                                        <button x-on:click="open = !open" x-show="!open"
                                            class="rounded-full w-7 items-center hover:bg-blue-400 text-lg">
                                            +
                                        </button>
                                        <div x-on:click="open = !open" x-show = "open"
                                            class="cursor-pointer hover:bg-blue-400">
                                            @foreach($option->getCompatibleOptions() as $compatibleOption)
                                                {{$compatibleOption}}<br>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="border-r border-gray-400">
                                        {{ html_entity_decode(str_replace("-","&#8209;",$option->getSsd())) }}
                                    </td>
                                    <td>{{ $option->getExamName() }}</td>
                                    <td class="text-sm">

                                        @foreach($option->getTakenExams() as $taken)
                                            {{ $taken->getExamName() }}({{ $taken->getSsd() }}): 
                                            {{ $taken->getActualCfu()}}/{{$taken->getCfu()}}
                                            <br>
                                        @endforeach
                                    </td>
                                    <td class="text-center">{{ $option->getCfu() }}</td>
                                    
                                    @if($startBlock)
                                        <?php $rows = $examBlock->getExamOptions()->count() ?>
                                        <td class="text-center border-l border-gray-400" 
                                            rowspan="{{ $rows }}">
                                                @if($examBlock->getExamOptions()->count()  == 1)
                                                    Obbligatorio.
                                                @else
                                                    <?php $numOptions = $examBlock->getNumExams() ?>
                                                    {{ $numOptions }} esam{{ $numOptions == 1 ? "e" : "i"}} a scelta.
                                                @endif
                                        </td>
                                        <td class="text-center font-semibold text-lg border-l border-gray-400" 
                                            rowspan="{{ $rows }}">
                                                {{ $option->getBlock()->getIntegrationValue()}}
                                        </td>
                                    @endif
                                   <?php $startBlock = false; ?>
                            @endforeach
                            <tr class = "border-t-4 border-black"/>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-panel>
        <x-panel>
            <div class="text-xl">
                <?php $cfu = $studyPlan->getRecognizedCredits() ?>
                <p>Totale CFU riconosciuti: {{ $cfu }}</p>
                <p>CFU da sostenere: {{ 180 - $cfu }}</p>
            </div>
        </x-panel>
    </x-mainpanel>
</x-app-layout>
