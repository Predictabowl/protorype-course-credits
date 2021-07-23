<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Integrazione') }}
        </h2>
    </x-slot>

    <x-mainpanel>
        <x-panel>
            <h1>Crediti Assegnati</h1>
            <div class="place-content-center">
                <table class="min-w-full rounded-lg">
                    <thead>
                        <tr class="bg-gray-100">
                            <th/>
                            <th>SSD</th>
                            <th>Nome</th>
                            <th>Esami Riconosciuti</th>
                            <th>CFU</th>
                            <th>Integrazione</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studyPlan->getexamBlocks() as $examBlock)
                        <tr class = "border-t-4 border-black" x-data="{ blockstart: 1 }"/>
                            <?php $startBlock = true; ?>
                            @foreach($examBlock->getExamOptions() as $option)
                                <tr class="hover:bg-blue-100 border-t border-gray-400">
                                    <td class="text-xs">
                                        @foreach($option->getCompatibleOptions() as $compatibleOption)
                                            {{$compatibleOption}}<br>
                                        @endforeach
                                    </td>
                                    <td>{{ $option->getSsd() }}</td>
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
                                        <td class="text-center" rowspan="{{ $option->getBlock()->getExamOptions()->count() }}">
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
    </x-mainpanel>
</x-app-layout>
