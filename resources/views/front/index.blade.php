<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista Prospetti
        </h2>
    </x-slot>

    <x-mainpanel>

        <x-panel>
            <div class="flex justify-between">
                <x-searchUserBar class="mb-4" filter="course" placeholder="Cerca per nome o email"/>
            </div>

            <div class="place-content-center">
                <table class="min-w-full rounded-lg">
                    <thead>
                        <tr class="bg-gray-100">
                            <th>Studente</th>
                            <th class="flex place-content-center">
                                <x-dropdown align="right" width="w-max">
                                    <x-slot name="trigger">
                                        {{-- <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"> --}}
                                        <button class="flex items-center text-sm hover:text-blue-600 text-gray-500 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                            <div class="font-bold text-base text-black hover:text-blue-600">
                                                Corso:
                                            </div>
                                            @if(isset($currentCourse))
                                                <div class="ml-2">
                                                    {{ $currentCourse->name }}
                                                </div>
                                            @endif
                                            <div class="ml-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <!-- Courses List -->
                                        <div class="text-left text-sm font-normal">
                                            <x-dropdown-link href="{{ route('frontIndex') }}/?{{http_build_query(
                                                    request()->except('course','page'))}}">
                                                Tutti
                                            </x-dropdown-link>
                                        @foreach($courses as $course)
                                            <?php $link = route('frontIndex')."/?".http_build_query(
                                                array_replace(request()->except('page'),['course' => $course->id]))
                                            ?>
                                            <x-dropdown-link :href="$link">
                                                {{ $course->name }}
                                            </x-dropdown-link>
                                        @endforeach
                                        </div>
                                    </x-slot>
                                </x-dropdown>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fronts as $front)
                        <tr class="hover:bg-blue-100 ">
                            <td>
                                <a class="hover:bg-blue-700 hover:text-white hover:font-semibold" href="{{ route('frontView',[$front])}}">{{ $front->user->name }}</a>
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
