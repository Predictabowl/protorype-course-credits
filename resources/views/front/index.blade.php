<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista Prospetti
        </h2>
    </x-slot>

    <x-mainpanel>

        <x-panel class="h-screen">
            <div class="md:flex justify-between mb-4">
                <x-searchUserBar filter="course" class="justify-center" placeholder="Cerca per nome o email" />
                <div class="flex bg-gray-100 rounded-xl px-3 py-2 items-center">
                    <x-dropdown align="sm_right" width="w-max" max_height="15rem">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center text-sm hover:text-blue-600
                                text-gray-500 focus:outline-none focus:text-gray-700
                                focus:border-gray-300 transition duration-150 ease-in-out">
                                <div class="text-base text-black hover:text-blue-600">
                                    {{ __('Course Filter') }}:
                                </div>
                                <div class="ml-2">
                                    @if (isset($currentCourse))
                                        {{ $currentCourse->name }}
                                    @else
                                        {{ __('All') }}
                                    @endif
                                </div>
                                <x-downArrow class="ml-1"/>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Courses List -->
                            <div class="text-left text-sm font-normal">
                                <x-dropdown-link
                                    href="{{ route('frontIndex') }}/?{{ http_build_query(request()->except('course', 'page')) }}">
                                    {{ __('All') }}
                                </x-dropdown-link>
                                @foreach ($courses as $course)
                                    <?php
                                        $link = route('frontIndex') . '/?' . http_build_query(
                                            array_replace(request()->except('page'), ['course' => $course->id]));
                                    ?>
                                    <x-dropdown-link :href="$link">
                                        {{ $course->name }}
                                    </x-dropdown-link>
                                @endforeach
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
            <x-table-fixed-header max_height="85%">
                <x-slot name="header">
                    <th> {{ __('Student') }}</th>
                    <th> {{ __('Course Chosen') }}</th>
                </x-slot>
                @foreach ($fronts as $front)
                    <tr class="tr-body">
                        <td>
                            <a class="td-link" href="{{ route('frontView', [$front]) }}">
                                {{ $front->user->name }}
                            </a>
                        </td>
                        <td>
                            @if (isset($front->course))
                                {{ $front->course->name }}
                            @else
                                {{ __('None Selected') }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </x-table-fixed-header>
            <div class="mt-4">
                {{ $fronts->links() }}
            </div>
        </x-panel>
    </x-mainpanel>
</x-app-layout>
