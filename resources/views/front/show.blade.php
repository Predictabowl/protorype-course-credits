<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Prospetto ({{ $front->user->name }})
        </h2>
    </x-slot>

    <x-mainpanel>
        <div class="sm:flex sm:justify-between">
            <x-panel class="sm:w-2/3">
                <div>
                    <form method="POST" action="{{ route('postTakenExam', [$front]) }}">
                        @csrf
                        <header class="flex justify-center">
                            <h2 class="ml-3 text-lg">{{ __('Add Taken Exam') }}</h2>
                        </header>
                        <div class="space-y-3">
                            <div class="mt-1">
                                <x-input type="text" class="w-full" name="name"  :placeholder="__('Teaching name')"
                                    :value="old('name')" required />

                                @error('name')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <x-input class="w-20" type="number" placeholder="cfu" name="cfu"
                                    :value="old('cfu')" required autofocus />
                                @error('cfu')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <x-input class="w-32" type="text" name="ssd" placeholder="SSD" list="ssd-list"
                                     value="{{ old('ssd') }}" autocomplete="off" required />

                                @error('ssd')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                                <datalist id="ssd-list">
                                    @foreach ($ssds as $ssd)
                                        <option value="{{ $ssd->code }}">
                                    @endforeach
                                </datalist>
                            </div>
                            <div>
                                <x-input class="w-20" type="number" name="grade" placeholder="{{ __('Grade') }}"
                                    value="{{ old('grade') }}" required />
                                @error('grade')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                            <x-button>
                                {{ __('Add') }}
                            </x-button>
                            <x-flash />
                        </div>
                    </form>
                </div>
            </x-panel>
            <x-panel class="flex-initial sm:items-center sm:ml-6 place-self-start">
                <div class="pr-2 text-lg">
                    {{ __('Degree Course') }}:
                </div>
                <x-dropdown align="right" width="w-max">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center font-medium text-gray-500 hover:text-gray-700
                                hover:border-gray-300 focus:outline-none focus:text-gray-700
                                focus:border-gray-300 transition duration-150 ease-in-out">
                            <div>
                                @if (isset($front->course))
                                    {{ $front->course->name }}
                                @else
                                    {{ __('None Selected') }}
                                @endif
                            </div>
                            <x-downArrow class="ml-1"/>
                        </button>
                        <x-flashStudyPlan />
                    </x-slot>

                    <x-slot name="content">
                        <!-- Courses List -->
                        @foreach ($courses as $course)
                            <form method="POST" action="{{ route('frontView', [$front]) }}">
                                @csrf
                                @method('PUT')
                                <x-dropdown-button type="submit" name="courseId" value="{{ $course->id }}">
                                    {{ $course->name }}
                                </x-dropdown-button>
                            </form>
                        @endforeach
                    </x-slot>
                </x-dropdown>
                <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                    <x-button-link href="{{ route('studyPlan', [$front]) }}">{{ __('Calculate Credits') }}
                    </x-button-link>

                </div>
            </x-panel>
        </div>


        {{-- Taken exams table --}}
        <x-panel class="overflow-y-scroll lg:max-h-screen" x-data="{ showConfirmationBox: false, formId: '-' }">
            <div class="flex gap-2 items-center">
                <h1 class="text-lg">
                    {{ __('Exams Taken') }}
                </h1>
                <form method="POST" id="formDeleteExams" action="{{ route('deleteFrontTakenExam', [$front]) }}">
                    @csrf
                    @method('DELETE')
                    <x-button-icon type="button" x-on:click="showConfirmationBox = true; formId = 'formDeleteExams'"
                        title="{{ __('Delete all Exams') }}">
                        <x-heroicon-o-trash class="h-6 w-6" />
                    </x-button-icon>
                </form>
            </div>
            <x-table-fixed-header aria_label="Lista esami inseriti">
                <x-slot name="header">
                    <tr class="bg-gray-100">
                        <th scope="col">ssd</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Grade') }}</th>
                        <th scope="col">CFU</th>
                    </tr>
                </x-slot>
                @foreach ($exams as $exam)
                    <tr class="hover:bg-blue-100">
                        <td>{{ $exam->getSsd() }}</td>
                        <td>{{ $exam->getExamName() }}</td>
                        <td class="text-center">{{ $exam->getGrade() }}/30</td>
                        <td class="text-center">{{ $exam->getCfu() }}</td>
                        <td class="w-10">
                            <form method="POST" action="{{ route('deleteTakenExam', [$front]) }}">
                                @csrf
                                @method('DELETE')
                                <x-button-icon name="exam" value="{{ serialize($exam) }}"
                                    title="{{ __('Delete') }}">
                                    <x-heroicon-m-trash class="h-5 w-5" />
                                </x-button-icon>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </x-table-fixed-header>

            {{-- Confirmation Box --}}
            <x-confirmation-box>
                {{ __('Delete all Exams') }}?
            </x-confirmation-box>
        </x-panel>
    </x-mainpanel>
</x-app-layout>
