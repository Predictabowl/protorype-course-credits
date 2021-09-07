<!DOCTYPE html>
<html>
<head>
 	<!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts (Apline) -->
    <script src="{{ asset('js/app.js') }}" defer></script>

{{-- 	<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

	<meta charset="utf-8">
	<title></title>
</head>
<body>
	Questo non funziona senza installare jnode<br>
	<button class="bg-gray-200 rounded-lg border border-black hover:bg-gray-400 p-1 m-2" x-on:click="alert('Hello');">
		Bottone
	</button>

	
	<div x-data="{ open: false }" class="ml-4" @mouseleave="open = false">
        <button @mouseover="open = true" class="border border-primary-900">Category</button>
        <div x-show="open" class="h-20 w-32 bg-red-900 text-white">
            <ul>
                <li>Sub-category 1</li>
                <li>Sub-category 2</li>
            </ul>
        </div>
    </div>


	<div class="grid grid-cols-auto w-2/3 m-auto">
		<div class="grid grid-cols-4 hover:bg-blue-100 border border-black text-center font-bold">
			<div>Nome</div>
			<div>Email</div>
			<div>Ruoli</div>
			<div>Data di creazione</div>
		</div>

		<div class="grid grid-cols-4 hover:bg-blue-100">
			<div>Mario Andretti</div>
			<div>mario@email.org</div>
			<div>admin,</div>
			<div>22 Agosto 2021</div>
		</div>
	</div>

</body>
</html>