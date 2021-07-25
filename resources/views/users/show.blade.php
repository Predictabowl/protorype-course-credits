<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ruoli  Utente
        </h2>
    </x-slot>

    <x-mainpanel >
        <header class="items-center text-xl text-center">
            <h2 class="m-2">{{ $user->name }}</h2>
            <h3 class="m-2">{{ $user->email }}</h3>
        </header>
        <x-panel class="w-max mx-auto">
            <div>
                <form method="POST" action="{{ route("userUpdate",[$user]) }}">
                    @csrf
                    @method("PUT")
                    {{-- To be changed with a dynamic role foreach --}}
                    <div class="ml-4">
                        <input type="checkbox" class="text-sm focus:outline-none focus:ring" name="admin" value="admin"
                            {{ $user->isAdmin() ? "checked" : ""}}>
                        <label class="ml-2" for="admin">Amministratore</label>
                    </div>
                    <div class="ml-4 mt-4">
                        <input type="checkbox" class="text-sm focus:outline-none focus:ring" name="supervisor" value="supervisor"
                            {{ $user->isSupervisor() ? "checked" : ""}}>
                        <label class="ml-2" for="supervisor">Supervisore</label>
                    </div>
                    <x-button class="mt-4 ml-6 self-center">
                        Conferma
                    </x-button>
                </form>
            </div>
        </x-panel>            
    </x-mainpanel>
</x-app-layout>
