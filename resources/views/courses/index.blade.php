<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{__("Courses List")}}
        </h2>
    </x-slot>

    <x-mainpanel>

        <x-panel>
            <div class="flex justify-between">
                <x-searchUserBar class="mb-4" filter="course" placeholder="Cerca per nome o email"/>
            </div>

            <div class="place-content-center">
                <table class="min-w-full rounded-lg">
                    <caption>Lista dei corsi di laurea disponibili</caption>
                    <thead>
                        <tr class="bg-gray-100">
                            <th> {{ __("Course") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        <tr class="hover:bg-blue-100 ">
                            <td>
                                <a class="hover:bg-blue-700 hover:text-white hover:font-semibold"
                                    href="{{ route('frontView',[$course])}}">
                                        {{ $course->name }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- <div class="mt-4">
                {{ $courses->links() }}
            </div> --}}
        </x-panel>
    </x-mainpanel>
</x-app-layout>
