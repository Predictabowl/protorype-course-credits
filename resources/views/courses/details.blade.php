<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $course->name }}
        </h2>
    </x-slot>

    <x-mainpanel>
        <x-panel>
            <div class="flex justify-center">
                <header class="text-2xl font-bold text-center p-1 rounded-xl w-max">
                    {{ $course->name }}
                </header>
            </div>
        </x-panel>
        <x-panel class="block max-h-screen overflow-scroll" x-data="{ showEditExam: 0, showNewExam: 0 }">
            @foreach ($course->examBlocks as $examBlock)
                <div class="border-2 border-orange-300 rounded-xl p-3 my-2">
                    <div class="md:flex justify-evenly">
                        <div>CFU: {{ $examBlock->cfu }}</div>
                        <div>Numero di Esami sceglibili: {{ $examBlock->max_exams }}</div>
                        <div>Anno di Corso: {{ $examBlock->courseYear }}</div>
                    </div>
                    <div class="border rounded-md">
                        Compatibilità:
                        {{-- <div class="grid lg:grid-cols-12 md:grid-cols-6 col-auto gap-2 "> --}}
                        @foreach ($examBlock->ssds as $ssd)
                            <span class="mx-1">{{ $ssd->code }}</span>
                        @endforeach
                    </div>
                    <div class="mt-2">
                        <div class="table w-full">
                            <div class="table-row tr-head font-bold text-center">
                                <div class="table-cell">Nome Esame</div>
                                <div class="table-cell">SSD</div>
                                <div class="table-cell">A scelta</div>
                            </div>
                            @foreach ($examBlock->exams as $exam)
                                <x-courses.exam-row :exam="$exam"/>
                            @endforeach
                            <!-- Add New Exam -->
                            <form class="table-row" x-show="showNewExam == {{ $examBlock->id }}" style="display: none"
                                    data-action="{{ route('examCreate', [$examBlock]) }}">
                                @csrf
                                <div class="table-cell">
                                    <x-input class="w-full" placeholder="Nome Esame" type="text" name="name"
                                        value="{{ old('nomeEsame') }}" required>
                                    </x-input>
                                </div>
                                <div class="table-cell text-center">
                                    <x-input class="w-32" placeholder="SSD" type="text" name="ssd"
                                        value="{{ old('ssd') }}">
                                    </x-input>
                                </div>
                                <div class="table-cell text-center">
                                    <input name="freeChoice"
                                        class="rounded-xl shadow-sm border-gray-300
                                                focus:border-indigo-300 focus:ring focus:ring-indigo-200
                                                focus:ring-opacity-50"
                                        type="checkbox" {{ old('freeChoice') ? 'checked' : '' }}>
                                </div>
                                <div class="table-cell align-middle text-center w-8" title="{{ __('Save') }}"">
                                    <x-buttons.confirmation-mini type="submit" />
                                </div>
                                <div class="table-cell align-middle text-center w-8" title="{{ __('Cancel') }}">
                                    <x-buttons.cancel-mini
                                        @click="showNewExam = (showNewExam == 0)
                                        ? showNewExam = {{ $examBlock->id }} : showNewExam = 0" />
                                </div>
                            </form>
                            <div class="table-row" x-show="showNewExam != {{ $examBlock->id }}">
                                <x-buttons.plus-mini size="7"
                                    @click="showNewExam = (showNewExam !=
                                        {{ $examBlock->id }} ? showNewExam = {{ $examBlock->id }} : showNewExam = 0)" />
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            {{-- @foreach ($course->examBlocks as $examBlock)
                <div class="border rounded-xl m-2">
                    <div>
                        {{$examBlock->cfu}}
                    </div>
                </div>
            @endforeach --}}
            {{-- <div x-show="showEditExam" class="fixed inset-0 grid place-content-center w-screen h-screen">
                <div class="max-w-max bg-gray-100 rounded-lg border border-black">
                    <div class="mt-2 mx-2 bg-white rounded-lg">
                        <form action="#">

                            <div>
                                <x-input placeholder="Nome Esame" class="w-full" type="text" value="{{ old('nomeEsame') }}" required >
                                </x-input>
                                @error('nomeEsame')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mt-2">
                                <x-input placeholder="SSD" class="w-32" type="text" value="{{ old('ssd') }}" required >
                                </x-input>
                                @error('ssd')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="flex justify-center gap-4 p-4">
                        <x-button type="button" x-on:click="document.getElementById(formId).submit(); showEditExam = false">
                            {{ __("Confirm") }}
                        </x-button>
                        <x-button type="button" x-on:click="showEditExam = false">
                            {{ __("Cancel") }}
                        </x-button>
                    </div>
                </div>
            </div> --}}
        </x-panel>
        <div id="flash-container"></div>
    </x-mainpanel>
    <script src="{{ asset('js/course.js') }}" defer></script>
</x-app-layout>
