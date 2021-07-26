<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista Prospetti
        </h2>
    </x-slot>

    <x-mainpanel>

        <x-panel>
            <div class="flex justify-between">
                <x-searchUserBar class="mb-4" filter="course"/>
                <div class="relative flex lg:inline-flex items-center bg-gray-100 rounded-xl px-3 mb-3 items-center">
                    <div class="mr-2">
                        Filtra per Corso di Laurea:
                   </div>
                
                    <x-dropdown align="right" width="w-max">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                <div>
                                    @if (request(["course"]))
                                        {{ request()->get("course") }}
                                    @else
                                        Tutti
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
                                <form method="GET" action="#">
                                    @csrf
                                    <x-dropdown-button>
                                        Tutti
                                    </x-dropdown-button>
                                </form>
                            @foreach($courses as $course)
                            {{-- Using the form we cannot retain current searches, only this one
                                 we could use a query builder link, I don't like that solution so
                                 I'll think about something else --}}
                                <form method="GET" action="#">
                                    @csrf
                                    <x-dropdown-button name="course" value="{{ $course->name }}">
                                        {{ $course->name }}
                                    </x-dropdown-button>
                                </form>
                            @endforeach
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <div class="place-content-center">
                <table class="min-w-full rounded-lg">
                    <thead>
                        <tr class="bg-gray-100">
                            <th>Studente</th>
                            <th>Corso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fronts as $front)
                        <tr class="hover:bg-blue-100">
                            <td>
                                <a class="hover:bg-blue-400" href="{{ route('frontView',[$front])}}">{{ $front->user->name }}</a>
                            </td>
                            <td>
                                @if(isset($front->course))
                                    {{ $front->course->name}}
                                @else
                                    Nessun corso selezionato
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-panel>
    </x-mainpanel>
</x-app-layout>
