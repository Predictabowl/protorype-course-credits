<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Prospetto') }}
        </h2>
    </x-slot>

    <x-mainpanel>
        <div class="sm:flex justify-between">
            <x-panel class="w-1/2">
                <div>
                    <form method="POST" action="/front/row">
                    {{--<form method="POST" action="/post/{{$post->slug}}/comments">--}}
                        @csrf
                        <header class="flex items-center">
                            {{-- <img src="https://i.pravatar.cc/60?u={{ auth()->id() }}" width="40" height="40" class="rounded-full"> --}}
                            <h2 class="ml-3">Aggiungi Esame Sostenuto</h2>
                        </header>
                        {{-- <div class="mt-4">
                            <textarea name="name" 
                                      class="w-full text-sm focus:outline-none focus:ring"
                                      rows="5" placeholder="Nome insegnamento." required></textarea>

                            @error("name")
                            <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div> --}}
                        <div class="mt-4">
                            <input type="text" class="text-sm w-full focus:outline-none focus:ring" name="name" placeholder="Nome insegnamento."
                                value="{{old("name")}}">

                            @error("name")
                            <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <input class="w-20"
                                type="number" name="cfu" placeholder="cfu" value="{{old("cfu")}}">

                            @error("cfu")
                            <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <input class="w-32"
                                type="text" class="text-sm focus:outline-none focus:ring" name="ssd" placeholder="SSD" 
                                value="{{old("ssd")}}">

                            @error("ssd")
                            <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                            <x-button>
                                Conferma
                            </x-button>
                        </div>
                    </form>
                </div>
            </x-panel>            
            <x-panel class="flex-initial sm:items-center sm:ml-6 place-self-start">
                <div class="pr-2">
                    Corso di Laurea:
               </div>
                <x-dropdown align="right" width="w-max">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <div>
                                @if(isset($front->course))
                                    {{ $front->course->name }}
                                @else
                                    Nessuno selezionato
                                @endif
                            </div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Courses List -->
                        @foreach($courses as $course)
                            <form method="POST" action="{{ route("frontView",[$front])}}">
                                @csrf
                                @method("PUT")
                            {{-- <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"> --}}
                          
                                {{-- <x-dropdown-link>                                            
                                    {{ $course->name }}
                                </x-dropdown-link>
                                <input type="hidden" name="courseId" value="{{$course->id}}"> --}}
                                <x-dropdown-button type="submit" name="courseId" value="{{ $course->id }}">
                                    {{ $course->name }}
                                </x-dropdown-button>
                            </form>
                        @endforeach
                    </x-slot>
                </x-dropdown>
                <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                    <x-button>
                        <a href="{{ route('studyPlan',[$front]) }}">Calcola Crediti</a>
                    </x-button>
                </div>
            </x-panel>
        </div>



        <x-panel>
            <h1>Esami Sostenuti</h1>
            <div class="place-content-center" x-data="{rowId = 1}">
                <table class="min-w-full rounded-lg">
                    <thead>
                        <tr class="bg-gray-100">
                            <th>ssd</th>
                            <th>Nome</th>
                            <th>CFU</th>
                            <th/>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exams as $exam)
                        <tr class="hover:bg-blue-100">
                            <td>{{ $exam->getSsd() }}</td>
                            <td>{{ $exam->getExamName() }}</td>
                            <td class="text-center">{{ $exam->getCfu() }}</td>
                            <td>
                                {{-- <a href="/front/remove/{{$exam->getId()}}" class="ml-6 text-xs font-bold uppercase hover:bg-gray-200">Remove</a> --}}
                                <form id="delete-row-{{$exam->getId()}}" method="POST" action="/front/row">
                                    @csrf
                                    @method("DELETE")
                                    <input type="hidden" name="id" value="{{$exam->getId()}}">
                                    <x-button-icon width="w-9" height="h-6" name="id" value="{{$exam->getId()}}">
                                        <img src="/images/delete-icon.svg" alt="Elimina">
                                    </x-button-icon>
                                </form>                                
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </form>
            </div>
        </x-panel>
    </x-mainpanel>
</x-app-layout>
