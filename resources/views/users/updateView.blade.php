<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestione Account
        </h2>
    </x-slot>

    <x-mainpanel>

        <x-panel class="sm:w-2/3 m-auto">
            <div class="place-content-center">
                <form method="POST" action="{{ route("userUpdate",[$user]) }}">
                    @csrf
                    @method("PUT")
                    <div class="mt-4">
                        <x-label for="name" :value="__('Name and Surname')" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" 
                            placeholder="{{ $user->name }}"
                            required autofocus />
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <x-button>
                            Modifica
                        </x-button>
                    </div>
                </form>
            </div>
        </x-panel>
    </x-mainpanel>
</x-app-layout>
