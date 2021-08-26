<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Valutazione Carriera</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    </head>
    <body>
        {{-- <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-10 sm:pt-0" --}}
        <div class="relative flex justify-center min-h-screen bg-gray-100 sm:items-center sm:pb-40">
{{--             @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 underline">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
 --}}
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="flex mx-auto justify-center w-80 pt-8 sm:justify-start sm:pt-0">
                    <img class="center" src="/images/logo_new.svg" alt="Università degli studi di Torino.">
                </div>

                <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <div class="ml-4 text-4xl leading-7 font-semibold">
                                <h1>Dipartimento di Giurisprundenza</h1>
                            </div>
                        </div>

                        <div class="ml-12 mt-6">
                            <h2 class="text-2xl">Valutazione Carriera - Prospetto riconoscimento esami</h2>
                            <div class="flex justify-center gap-4 mt-4 pt-4 border-t border-gray-200">
                                @if (Route::has('login'))
                                    @auth
                                        <x-button-link href="{{ url('/dashboard') }}"> {{ __("Dashboard") }} </x-button>
                                    @else
                                        <x-button-link href="{{ route('login') }}"> {{ __("Log in") }} </x-button>
                                        @if (Route::has('register'))
                                           <x-button-link href="{{ route('register') }}"> {{ __("Register") }} </x-button>
                                        @endif
                                    @endauth
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl"> Test Infos</h1>
                    Locale: {{app()->currentLocale()}}
                    <p>
                        {{__("Forgot your password?")}}
                    </p>
                </div>

{{--                 <div class="flex justify-center mt-4 sm:items-center sm:justify-between">


                    <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </div>
                </div> --}}
            </div>
        </div>
    </body>
</html>
