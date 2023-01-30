<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{__("Courses List")}}
        </h2>
    </x-slot>

    <x-mainpanel>
        <style>
            .examGrid{
                display: grid;
                grid-template-columns: repeat(3, auto);
            }
        </style>

        <x-panel  x-data="{ showEditExam : 0 }">
            <div class="flex justify-center">
                <header class="text-2xl font-bold text-center p-1 rounded-xl w-max">
                    {{$course->name}}
                </header>
            </div>
            @foreach ($course->examBlocks as $examBlock)
                <div class="border border-2 rounded-xl p-3 my-2">
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
                        <table class="w-full">
                            <thead>
                                <tr class="tr-head">
                                    <th>Nome Esame</th>
                                    <th>SSD</th>
                                    <th>A scelta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($examBlock->exams as $exam)

                                    <tr class="tr-body" x-show="showEditExam != {{$exam->id}}">
                                        <td> {{ $exam->name}} </td>
                                        <td class="text-center">
                                            @isset($exam->ssd)
                                                {{$exam->ssd->code}}
                                            @endisset
                                        </td>
                                        <td class="text-center">
                                            @if ($exam->free_choice)
                                                {{__("Yes")}}
                                            @else
                                                {{__("No")}}
                                            @endif
                                        </td>
                                        <td class="w-10">
                                            <x-link-icon href="#" @click="showEditExam = (showEditExam != {{$exam->id}}) ? showEdit = {{$exam->id}} : showEdit = 0">
                                                <x-heroicon-m-pencil-square class="h-5 w-5" />
                                            </x-link-icon>
                                        </td>
                                        <td class="w-10">
                                            <x-link-icon href="#">
                                                <x-heroicon-m-trash class="h-5 w-5" />
                                            </x-link-icon>
                                        </td>
                                    </tr>
                                    <!-- Hidden row -->
                                    <tr x-show="showEditExam == {{$exam->id}}" style="display: none;">
                                        <td>
                                            <x-input class="w-full" placeholder="Nome Esame" type="text"
                                                value="{{ $exam->name}}" required >
                                            </x-input>
                                        </td>
                                        <td class="text-center">
                                            <x-input class="w-32" placeholder="SSD" type="text"
                                                value="{{ isset($exam->ssd) ? $exam->ssd->code : ''}}">
                                            </x-input>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $checked = "checked";

                                                // if($exam->free_choice){
                                                //  } else {
                                                //     $checked = "";
                                                //  }
                                            @endphp
                                            <input class="rounded-xl shadow-sm border-gray-300
                                            focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="checkbox" {{ $exam->free_choice ? "checked" : ""}}>
                                            {{-- <x-input type="checkbox" checked>
                                            </x-input> --}}
                                        </td>
                                        <td class="w-10">
                                            <x-link-icon href="#" class="rounded-2xl">
                                                <x-heroicon-m-check-circle class="h-5 w-5 text-green-700" />
                                            </x-link-icon>
                                        </td>
                                        <td class="w-10">
                                            <x-link-icon href="#" @click="showEditExam = (showEditExam == 0) ? showEdit = {{$exam->id}} : showEdit = 0">
                                                <x-heroicon-m-x-circle class="h-5 w-5 text-red-700" />
                                            </x-link-icon>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
