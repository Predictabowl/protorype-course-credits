<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista Prospetti
        </h2>
    </x-slot>

    <x-mainpanel>

        <x-panel>
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
