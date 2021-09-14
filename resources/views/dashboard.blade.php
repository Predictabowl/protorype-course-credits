<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Heading') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="ml-12">
                        <h2 class="text-2xl font-bold">Valutazione Carriera - Prospetto riconoscimento esami</h2>
                        <div class="mt-2 text-gray-600 dark:text-gray-400 mb-4">
                            <x-legal-heading/>
                        </div>
                        <div>
                            <a class="underline text-blue-500 hover:text-blue-700" 
                                href="{{route('frontPersonal')}}">{{ __("Fill in the Prospectus") }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
