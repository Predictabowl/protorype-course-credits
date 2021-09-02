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
                        <div class="mt-2 text-gray-600 dark:text-gray-400">
                            <p class="mb-4">
                                La presente bozza di riconoscimento esami è predisposta dagli Uffici di Segreteria Didattica del Dipartimento di Giurisprudenza e la sua validità è subordinata all’approvazione da parte della Commissione riconoscimento esami del corso di laurea, nonché alla correttezza e alla completezza delle informazioni (CFU, nome dell'insegnamento, SSD, etc.) contenute nell'autocertificazione trasmessa agli Uffici.
                            </p>
                            <p class="mb-4">
                                In particolare si invitano gli studenti a controllare con molta attenzione il contenuto, per verificare che i dati in esse imputati corrispondano esattamente agli insegnamenti sostenuti e dei quali si richiede il riconoscimento.
                            </p>
                        </div>
                        <div>
                            <a class="underline text-blue-500 hover:text-blue-700" 
                                href="{{route('frontPersonal')}}">Compila Prospetto
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
