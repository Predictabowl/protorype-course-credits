<x-full-layout>

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
</x-full-layout>
