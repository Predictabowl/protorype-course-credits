<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in!
                    <p>
                        Amministratore: {{ auth()->user()->isAdmin()? "true" : "false" }} <br>
                        Supervisore: {{auth()->user()->isSupervisor()? "true" : "false"}}
                    </p>
                    <p class="hover:underline text-blue-500">
                        <a href="{{ route("courseOptions") }}">Lista Esami</a>
                    </p>
                    <p class="hover:underline text-blue-500">
                        <a href="{{ route("frontPersonal") }}">Compila Prospetto</a>
                    </p>
                    <p class="hover:underline text-blue-500">
                        <a href=" {{ route("studyPlan") }}">Vedi tabella integrazione</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
