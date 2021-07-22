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
                        <tr class = "border-t border-black"/>
                            @foreach($examBlock->getApprovedExams() as $examOption)
                                <tr class="hover:bg-blue-100">
                                    <td>
                                        @foreach($examOption->getExamOption()->getCompatibleOptions() as $option)
                                            {{$option}},
                                        @endforeach
                                    </td>
                                    <td>{{ $examOption->getExamOption()->getSsd() }}</td>
                                    <td>{{ $examOption->getExamOption()->getExamName() }}</td>
                                    <td>
                                        @foreach(($examOption->getTakenExams() as $taken)
                                            {{$taken->getTakenExam()->getSsd()}}: {{ $taken->getActualCfu()}}/{{$taken->getTakenExam()->getCfu()}}, 
                                        @endforeach
                                    </td>
                                    <td class="text-center">{{ $examOption->gtExamOption()->getCfu() }}</td>
                                    <td>{{ $exam->getIntegrationValue()}}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-panel>
    </x-mainpanel>
</x-app-layout>
