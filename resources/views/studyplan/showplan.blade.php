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
                            <td>{{ $exam->getExamOption()->getSsd() }}</td>
                            <td>{{ $exam->getExamOption()->getExamName() }}</td>
                            <td class="text-center">{{ $exam->getExamOption()->getCfu() }}</td>
                            <td>
                                @foreach($exam->getExamOption()->getCompatibleOptions() as $option)
                                    {{$option}},
                                @endforeach
                            </td>
                            <td>
                                @foreach($exam->getTakenExams() as $taken)
                                    {{$taken->getTakenExam()->getSsd()}}: {{ $taken->getActualCfu()}}/{{$taken->getTakenExam()->getCfu()}}, 
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
                            <th>ssd</th>
                            <th>Nome</th>
                            <th>CFU</th>
                            <th>Compatibilità</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($examBlocks as $examBlock)
                        <tr class = "border-t border-black"/>
                            @foreach($examBlock->getExamOptions() as $examOption)
                                <tr class="hover:bg-blue-100">
                                    <td>{{ $examOption->getSsd() }}</td>
                                    <td>{{ $examOption->getExamName() }}</td>
                                    <td class="text-center">{{ $examOption->getCfu() }}</td>
                                    <td>
                                        @foreach($examOption->getCompatibleOptions() as $option)
                                            {{$option}},
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-panel>
    </x-mainpanel>
</x-app-layout>
