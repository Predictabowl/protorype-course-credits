<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestione Utenti
        </h2>
    </x-slot>

    <x-mainpanel>

        <x-panel  x-data="{ showConfirmationBox: false, formId: ''}">
            <div class="place-content-center">
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
                        <tr class="tr-body">
                            <td> {{ $user->name }} </td>
                            <td> {{ $user->email}} </td>
                            <td>
                                @php
                                    echo $user->roles->implode("name", ", ");
                                @endphp
                            </td>
                            <td class="text-center"> {{ $user->created_at->isoFormat("DD MMMM YYYY") }}</td>
                            <td class="w-10">
                                <a href="{{route("userShow",[$user])}}">
                                    <x-button-icon name="id" title="{{__('Edit')}}">
                                        <x-heroicon-m-pencil-square class="w-5 h-5"/>
                                    </x-button-icon>
                                </a>
                            </td>
                            <td class="w-10">
                                @can("delete", $user)
                                    <form id="form-{{$user->id}}" method="POST" action="{{ route("userDelete",[$user]) }}">
                                        @csrf
                                        @method("DELETE")
                                        <x-button-icon type="button" name="id" title="{{__('Delete')}}"
                                        x-on:click="
                                        showConfirmationBox = true;
                                        $refs.boxName.innerHTML = '{{$user->name}}';
                                        formId = 'form-{{ $user->id }}'">
                                            <x-heroicon-m-trash class="h-5 w-5"/>
                                        </x-button-icon>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Confirmation Box --}}
            <x-confirmation-box>
                {{ __("Delete") }} <span x-ref="boxName" class="font-bold"></span>?
            </x-confirmation-box>

            {{-- Page links --}}
            <div class="mt-4">
                {{ $users->links() }}
            </div>

            <x-flash/>
        </x-panel>
    </x-mainpanel>
</x-app-layout>
