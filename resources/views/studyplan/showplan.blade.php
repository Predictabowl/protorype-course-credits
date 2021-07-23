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
                            <th>ssd</th>
                            <th>Nome</th>
                            <th>CFU</th>
                            <th>Compatibilità</th>
                            <th>Esami riconosciuti</th>
                            <th>Integrazione</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studyPlan->getExams() as $exam)
                        <tr class="hover:bg-blue-100">
                            <td>{{ $exam->getSsd() }}</td>
                            <td>{{ $exam->getExamName() }}</td>
                            <td class="text-center">{{ $exam->getCfu() }}</td>
                            <td>
                                @foreach($exam->getCompatibleOptions() as $option)
                                    {{$option}},
                                @endforeach
                            </td>
                            <td>
                                @foreach($exam->getTakenExams() as $taken)
                                    {{$taken->getSsd()}}: {{ $taken->getActualCfu()}}/{{$taken->getCfu()}}, 
                                @endforeach
                            </td>
                            <td>{{ $exam->getIntegrationValue()}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-panel>
        <x-panel>
            <h1>Corso</h1>
            <div class="place-content-center">
                <table class="min-w-full rounded-lg">
                    <thead>
                        <tr class="bg-gray-100">
                            <th>Compatibilità</th>
                            <th>ssd</th>
                            <th>Nome</th>
                            <th>Esami Riconosciuti</th>
                            <th>CFU</th>
                            <th>Integrazione</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studyPlan->getexamBlocks() as $examBlock)
                        <tr class = "border-t-2 border-black"/>
                            @foreach($examBlock->getExamOptions() as $option)
                                <tr class="hover:bg-blue-100 border-t border-gray-400">
                                    <td>
                                        @foreach($option->getCompatibleOptions() as $compatibleOption)
                                            {{$compatibleOption}},
                                        @endforeach
                                    </td>
                                    <td>{{ $option->getSsd() }}</td>
                                    <td>{{ $option->getExamName() }}</td>
                                    <td>

                                        @foreach($option->getTakenExams() as $taken)
                                            {{$taken->getSsd()}}: {{ $taken->getActualCfu()}}/{{$taken->getCfu()}}, 
                                        @endforeach
                                    </td>
                                    <td class="text-center">{{ $option->getCfu() }}</td>
                                    <td class="text-center">{{ $option->getIntegrationValue()}}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-panel>
    </x-mainpanel>
</x-app-layout>
