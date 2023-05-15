<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Courses List') }}
        </h2>
    </x-slot>

    <x-mainpanel>

        <x-panel>
            <div class="flex justify-between mb-4">
                <x-searchUserBar filter="course" placeholder="Cerca per nome" />
                <x-button-link id="new-course-link" href="{{ route('courseNew') }}">
                    {{ __('Create New Course') }}
                </x-button-link>
            </div>
            <x-table-fixed-header aria_label="Tabella dei corsi disponibili">
                <x-slot name="header">
                    <th scope="col">{{ __('Course') }}</th>
                    <th scope="col">Cfu</th>
                    <th scope="col">Prova finale</th>
                    <th scope="col">Altre attività</th>
                    <th scope="col">Anni</th>
                    <th scope="col">Cfu Riconoscibili</th>
                    <th scope="col">Cfu annuali</th>
                    <th scope="col">{{__("Active")}}</th>
                </x-slot>
                @foreach ($courses as $course)
                    <tr class="tr-body">
                        <td>
                            <a class="td-link" href="{{ route('courseDetails', [$course]) }}">
                                {{ $course->name }}
                            </a>
                        </td>
                        <td class="text-center">{{ $course->cfu }}</td>
                        <td class="text-center">{{ $course->finalExamCfu }}</td>
                        <td class="text-center">{{ $course->otherActivitiesCfu }}</td>
                        <td class="text-center">{{ $course->numberOfYears }}</td>
                        <td class="text-center">{{ $course->maxRecognizedCfu }}</td>
                        <td class="text-center">{{ $course->cfuTresholdForYear }}</td>
                        <td>
                            @if($course->active)
                                <x-heroicon-o-check class="h-6 w-6 text-green-700 m-auto" />
                            @else
                                <x-heroicon-o-x-mark class="h-6 w-6 text-red-700 m-auto" />
                            @endif
                        </td>
                    </tr>
                @endforeach
            </x-table-fixed-header>
        </x-panel>
    </x-mainpanel>
</x-app-layout>
