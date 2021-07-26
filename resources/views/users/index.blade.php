<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestione Utenti
        </h2>
    </x-slot>

    <x-mainpanel>

        <x-panel>
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
                <x-searchUserBar class="mb-4" placeholder="Cerca utente" filter="role"/>
                <table class="min-w-full rounded-lg">
                    <thead>
                        <tr class="bg-gray-100">
                            <th>Utente</th>
                            <th>Email</th>
                            <th>Ruoli</th>
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
                            <td class="w-10">
                                <a href="{{route("userShow",[$user])}}">
                                    <x-button-icon width="w-9" height="h-6" name="id">
                                        <img src="/images/edit-icon.svg" alt="Elimina">
                                    </x-button-icon>
                                </a>
                            </td>
                            <td class="w-10">
                                <form method="POST" action="{{ route("userDelete",[$user]) }}">
                                    @csrf
                                    @method("DELETE")
                                    <x-button-icon width="w-9" height="h-6" name="id" >
                                        <img src="/images/delete-icon.svg" alt="Elimina">
                                    </x-button-icon>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-panel>
    </x-mainpanel>
</x-app-layout>
