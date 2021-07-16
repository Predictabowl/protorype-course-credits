<x-full-layout>

    <x-panel>
        <form method="POST" action="#">
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
                <input type="text" class="text-sm focus:outline-none focus:ring" name="name" placeholder="Nome insegnamento.">

                @error("name")
                <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <input type="number" name="cfu" placeholder="cfu">

                @error("cfu")
                <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <input type="text" class="text-sm focus:outline-none focus:ring" name="ssd" placeholder="SSD">

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
    </x-panel>

    <x-panel>
        <h1>Esami Sostenuti</h1>
        <div class="place-content-center">
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
                    <tr>
                        <td>{{ $exam->getSsd() }}</td>
                        <td>{{ $exam->getExamName() }}</td>
                        <td class="text-center">{{ $exam->getCfu() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-panel>
</x-full-layout>
