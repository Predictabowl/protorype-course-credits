<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{__("Courses List")}}
        </h2>
    </x-slot>

    <x-mainpanel>
        <x-panel  x-data="{ showEditExam : 0, showNewExam : 0 }">
            <div class="flex justify-center">
                <header class="text-2xl font-bold text-center p-1 rounded-xl w-max">
                    {{$course->name}}
                </header>
            </div>
            @foreach ($course->examBlocks as $examBlock)
                <div class="border-2 rounded-xl p-3 my-2">
                    <div class="md:flex justify-evenly">
                        <div>CFU: {{$examBlock->cfu}}</div>
                        <div>Numero di Esami sceglibili: {{$examBlock->max_exams}}</div>
                        <div>Anno di Corso: {{$examBlock->courseYear}}</div>
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

                                    <div class="table-row tr-body" x-show="showEditExam != {{$exam->id}}">
                                        <div class="table-cell italic"> {{ $exam->name}} </div>
                                        <div class="table-cell text-center">
                                            @isset($exam->ssd)
                                                {{$exam->ssd->code}}
                                            @endisset
                                        </div>
                                        <div class="table-cell text-center">
                                            @if ($exam->free_choice)
                                                {{__("Yes")}}
                                            @else
                                                {{__("No")}}
                                            @endif
                                        </div>
                                        <div class="table-cell w-8" title="{{__("Edit")}}">
                                            <x-button-icon type="button"
                                                    @click="showEditExam = (showEditExam != {{$exam->id}})
                                                        ? showEditExam = {{$exam->id}} : showEditExam = 0">
                                                <x-heroicon-m-pencil-square class="h-5 w-5"/>
                                            </x-button-icon>
                                        </div>
                                        <div class="table-cell w-8">
                                            <x-dropdown width="24">
                                                <x-slot name="trigger">
                                                    <x-button-icon type="button">
                                                        <x-heroicon-m-ellipsis-horizontal class="h-5 w-5" />
                                                    </x-button-icon>
                                                </x-slot>
                                                <x-slot name="content">
                                                    <x-dropdown-button>
                                                        {{__("Delete")}}
                                                    </x-dropdown-button>
                                                </x-slot>
                                            </x-dropdown>
                                        </div>
                                    </div>
                                    <!-- Hidden row -->
                                    <div class="table-row" x-show="showEditExam == {{$exam->id}}" style="display: none">
                                        <div class="table-cell">
                                            <x-input class="w-full" placeholder="Nome Esame" type="text"
                                                value="{{ $exam->name}}" required >
                                            </x-input>
                                        </div>
                                        <div class="table-cell text-center">
                                            <x-input class="w-32" placeholder="SSD" type="text"
                                                value="{{ isset($exam->ssd) ? $exam->ssd->code : ''}}">
                                            </x-input>
                                        </div>
                                        <div class="table-cell text-center">
                                            <input class="rounded-xl shadow-sm border-gray-300
                                                focus:border-indigo-300 focus:ring focus:ring-indigo-200
                                                focus:ring-opacity-50"
                                                type="checkbox" {{ $exam->free_choice ? "checked" : ""}}>
                                        </div>
                                        <div class="table-cell align-middle text-center w-8" title="{{__("Save")}}">
                                            <x-buttons.confirmation-mini/>
                                        </div>
                                        <div class="table-cell align-middle text-center w-8" title="{{__("Cancel")}}">
                                            <x-buttons.cancel-mini @click="showEditExam = (showEditExam == 0)
                                                ? showEditExam = {{$exam->id}} : showEditExam = 0"/>
                                        </div>
                                    </div>
                                @endforeach
                                <!-- Add New Exam -->
                                <div x-show="showNewExam != {{$examBlock->id}}">
                                    <x-buttons.plus-mini size="7" @click="showNewExam = (showNewExam !=
                                        {{$examBlock->id}} ? showNewExam = {{$examBlock->id}} : showNewExam = 0)" />
                                </div>
                                <div class="table-row" x-show="showNewExam == {{$examBlock->id}}" style="display: none">
                                    <div class="table-cell">
                                        <x-input class="w-full" placeholder="Nome Esame" type="text"
                                            value="{{ old('nomeEsame') }}" required >
                                        </x-input>
                                    </div>
                                    <div class="table-cell text-center">
                                        <x-input class="w-32" placeholder="SSD" type="text"
                                            value="{{ old('ssd')}}">
                                        </x-input>
                                    </div>
                                    <div class="table-cell text-center">
                                        <input class="rounded-xl shadow-sm border-gray-300
                                            focus:border-indigo-300 focus:ring focus:ring-indigo-200
                                            focus:ring-opacity-50"
                                            type="checkbox" {{ old('freeChoice') ? "checked" : ""}}>
                                    </div>
                                    <div class="table-cell align-middle text-center w-8" title="{{__('Save')}}"">
                                        <x-buttons.confirmation-mini/>
                                    </div>
                                    <div class="table-cell align-middle text-center w-8" title="{{__('Cancel')}}">
                                        <x-buttons.cancel-mini @click="showNewExam = (showNewExam == 0)
                                            ? showNewExam = {{$examBlock->id}} : showNewExam = 0"/>
                                    </div>
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
    </x-mainpanel>
</x-app-layout>
