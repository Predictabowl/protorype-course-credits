<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Prospetto ({{ $front->user->name }})
        </h2>
    </x-slot>

    <x-mainpanel>
        <div class="sm:flex justify-between">
            <x-panel class="w-1/2">
                <div>
                    <form method="POST" action="{{ route("postTakenExam",[$front]) }}">
                        @csrf
                        <header class="flex items-center">
                            <h2 class="ml-3 text-lg">{{ __("Add Taken Exam") }}</h2>
                        </header>
                        <div class="mt-3">
                            <x-input type="text" class="w-full" name="name" :placeholder="__('Teaching name')" 
                                :value="old('name')"/>

                            @error("name")
                            <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <x-input class="w-20" type="number" placeholder="cfu" name="cfu" :value="old('cfu')" required autofocus />
                            @error("cfu")
                               <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <x-input class="w-32" type="text" name="ssd" placeholder="SSD" 
                                value="{{old('ssd')}}"/>

                            @error("ssd")
                            <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                            <x-button>
                                {{ __("Confirm") }}
                            </x-button>
                            <x-flash/>
                        </div>
                    </form>
                </div>
            </x-panel>            
            <x-panel class="flex-initial sm:items-center sm:ml-6 place-self-start">
                <div class="pr-2">
                    {{ __("Degree Course") }}:
               </div>
                <x-dropdown align="right" width="w-max">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <div>
                                @if(isset($front->course))
                                    {{ $front->course->name }}
                                @else
                                    {{ __("None Selected") }}
                                @endif
                            </div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                        <x-flashStudyPlan/>
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
                    <x-button-link href="{{ route('studyPlan',[$front]) }}">Calcola Crediti</x-button-link>
                    
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exams as $exam)
                        <tr class="hover:bg-blue-100">
                                <td>{{ $exam->getSsd() }}</td>
                                <td >{{ $exam->getExamName() }}</td>
                                <td class="text-center">{{ $exam->getCfu() }}</td>
                                <td class="w-10">
                                    {{-- <a href="/front/remove/{{$exam->getId()}}" class="ml-6 text-xs font-bold uppercase hover:bg-gray-200">Remove</a> --}}
                                    <form method="POST" action="{{ route("deleteTakenExam",[$front]) }}">
                                        @csrf
                                        @method("DELETE")
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
