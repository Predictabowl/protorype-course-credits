<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestione Utenti
        </h2>
    </x-slot>

    <x-mainpanel>

        <x-panel  x-data="{ showmodal: false, formId: ''}">
            <div class="place-content-center">
{{--                 <div class="relative mb-4 flex lg:inline-flex items-center bg-gray-100 rounded-xl px-3 py-2">
                    <form method="GET" action="#">
                        @if(request("category"))
                            <input type="hidden" name="category" value="{{request("category")}}">
                        @endif
                        <input type="text" name="search" placeholder="Cerca utente"
                            class="bg-transparent placeholder-black font-semibold text-sm"
                            value="{{ request("search") }}">
                    </form>
                </div> --}}
                <div class="mb-4">
                    <x-searchUserBar placeholder="Cerca per nome o email" filter="role"/>
                </div>
                <table class="min-w-full rounded-lg">
                    <thead>
                        <tr class="bg-gray-100">
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Ruoli</th>
                            <th>Data creazione</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr class="hover:bg-blue-100 active:bg-blue-400">
                            <td> {{ $user->name }} </td>
                            <td> {{ $user->email}} </td>
                            <td> 
                                @foreach($user->roles as $role)
                                    {{ $role->name }}, 
                                @endforeach 
                            </td>
                            <td class="text-center"> {{ $user->created_at->isoFormat("DD MMMM YYYY") }}</td>
                            <td class="w-10">
                                <a href="{{route("userShow",[$user])}}">
                                    <x-button-icon width="w-9" height="h-6" name="id">
                                        <img src="/images/edit-icon.svg" alt="Elimina">
                                    </x-button-icon>
                                </a>
                            </td>
                            <td class="w-10">
                                <form id="form-{{$user->id}}" method="POST" action="{{ route("userDelete",[$user]) }}">
                                    @csrf
                                    @method("DELETE")
                                    <x-button-icon type="button" 
                                        x-on:click=" 
                                            showmodal = true;
                                            $refs.boxName.innerHTML = '{{$user->name}}';
                                            formId = 'form-{{ $user->id }}'" 
                                        width="w-9" height="h-6" name="id" >
                                        <img src="/images/delete-icon.svg" alt="Elimina">
                                    </x-button-icon>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Confirmation box --}}
            <div x-show="showmodal" class="fixed inset-x-0 max-w-max m-auto bg-gray-100 rounded-lg border border-black ">
                <div class="text-center mt-2 mx-2 bg-white rounded-lg">
                    Cancellare <span x-ref="boxName" class="font-bold"></span>?
                </div>
                <div class="flex justify-center gap-4 p-4">
                    <x-button type="button" x-on:click="document.getElementById(formId).submit();">
                        {{ __("Confirm") }}
                    </x-button>
                    <x-button type="button" x-on:click="showmodal = false">
                        {{ __("Cancel") }}
                    </x-button>
                </div>
            </div>

            {{-- Page links --}}
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </x-panel>
    </x-mainpanel>
</x-app-layout>
