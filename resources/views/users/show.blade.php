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
                <form method="POST" action="{{ route("userRoleUpdate",[$user]) }}">
                    @csrf
                    @method("PUT")

                    <div class="flex flex-col gap-4">
                        {{-- To be changed with a dynamic role foreach --}}
                        @if($isAdminToggable)
                            <div>
                                <input type="checkbox" class="text-sm focus:outline-none focus:ring" id="admin"
                                    name="{{ App\Models\Role::ADMIN }}"
                                    {{ $user->isAdmin() ? "checked" : "" }}>
                                <label class="ml-2" for="admin"> {{ __("Administrator") }}</label>
                            </div>
                        @elseif($user->isAdmin())
                            <div class="text-gray-700">
                                {{ __("Administrator") }}
                            </div>
                        @endif
                        <div>
                            <input type="checkbox" class="text-sm focus:outline-none focus:ring" id="supervisor"
                                name="{{ App\Models\Role::SUPERVISOR }}"
                                {{ $user->isSupervisor() ? "checked" : ""}}>
                            <label class="ml-2" for="supervisor">Supervisore</label>
                        </div>
                    </div>
                    <x-button class="mt-4">
                        {{ __("Confirm") }}
                    </x-button>
                </form>
            </div>
        </x-panel>
    </x-mainpanel>
</x-app-layout>
