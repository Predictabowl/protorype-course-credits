<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $course->name }}
        </h2>
    </x-slot>

    <x-mainpanel>
        <x-panel>
            <div class="md:flex justify-center gap-10">
                <div>
                    @php
                        $courseData = [
                            'CFU' => $course->cfu,
                            'Prova Finale' => $course->finalExamCfu,
                            'Altre Attività' => $course->otherActivitiesCfu,
                            'Numero di anni' => $course->numberOfYears,
                            'Massimo numero di crediti riconoscibili' => $course->maxRecognizedCfu,
                            'Crediti minimi richiesti per anno accademico' => $course->cfuTresholdForYear,
                        ];
                    @endphp
                    <table aria-label="Dati Corso">
                        <tbody>
                            @foreach ($courseData as $key => $value)
                                <tr {{ $loop->even ? 'class = bg-green-50' : '' }}>
                                    <th class="text-left">{{ $key }}</th>
                                    <td class="text-center">{{ $value }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="flex flex-col justify-center items-center gap-2">
                    <div class="flex gap-2 rounded-md p-1">
                        <div>
                            <div>
                                <x-label>
                            </div>
                                {{__("Active")}}:
                            </x-label>
                        </div>
                        <div class="rounded-xl">
                            <form action="{{route('courseActivate',[$course->id])}}" method="POST">
                                @csrf
                                @method("PUT")
                                <input type="checkbox" name="active"
                                    class="rounded-xl text-green-700 w-6 h-6"
                                    {{$course->active ? "checked" : ""}}
                                    onChange="this.form.submit()"/>
                            </form>
                        </div>
                    </div>
                    <div>
                        <x-button-link href="{{route('courseShow',[$course->id])}}">
                            {{ __("Edit") }}
                        </x-button-link>
                        {{-- <x-link-icon href="{{route('courseShow',[$course->id])}}"
                                id="{{'edit-course-'.$course->id}}">
                            <x-heroicon-m-pencil-square class="h-5 w-5" />
                        </x-link-icon> --}}
                    </div>
                </div>
            </div>
        </x-panel>
        <x-panel class="block max-h-screen overflow-scroll"
                x-data="{ showEditForm: 0, showNewExam: 0, showNewExamBlock: false }">
            @foreach ($course->examBlocks as $examBlock)
                <x-courses.exam-block-row :examBlock="$examBlock" />
            @endforeach
            <meta class="insert-point">
            <form x-show="showNewExamBlock" style="display: none" data-method="POST"
                    data-action="{{ route('examBlockCreate',[$course])}}" >
                @csrf
                <div class="border-2  rounded-xl table w-full p-5 my-2 text-center">
                    <div class="table-row">
                        <div class="table-cell">CFU</div>
                        <div class="table-cell">N. esami sceglibili</div>
                        <div class="table-cell">Anno di Corso</div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell">
                            <x-input class="w-32" type="number" name="cfu" value="{{ old('cfu') }}" required>
                            </x-input>
                        </div>
                        <div class="table-cell">
                            <x-input class="w-32" type="number" name="maxExams" value="{{ old('maxExams') }}"
                                required>
                            </x-input>
                        </div>
                        <div class="table-cell">
                            <x-input class="w-32" type="number" name="courseYear" value="{{ old('courseYear') }}">
                            </x-input>
                        </div>
                        <div class="table-cell align-middle w-10">
                            <x-buttons.confirmation-outline type="submit" onclick="submitForm(event,this)" />
                        </div>
                        <div class="table-cell align-middle w-10">
                            <x-buttons.cancel-outline @click="showNewExamBlock = false" />
                        </div>
                    </div>
                </div>
            </form>
            <div x-show="!showNewExamBlock" class="flex justify-center items-center h-max">
                <x-button @click="showNewExamBlock = true">
                    {{ __('Add Exam Block') }}
                </x-button>
            </div>
        </x-panel>
        <div class="p-0 m-0" id="flash-container"></div>
    </x-mainpanel>
    <script src="{{ asset('js/course.js') }}" defer></script>
</x-app-layout>
