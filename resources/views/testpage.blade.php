<!DOCTYPE html>
<html>
<head>
	<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
	<meta charset="utf-8">
	<title></title>
</head>
<body>
	Questo non funziona senza installare jnode<br>
	<button x-on:click="alert('Hello')">
		Bottone
	</button>

	<p>
	<div x-data="{ open: false }" class="ml-4" @mouseleave="open = false">
        <button @mouseover="open = true" class="border border-primary-900">Category</button>
        <div x-show="open" class="h-80 bg-red-900">
            <ul>
                <li>Sub-category 1</li>
                <li>Sub-category 2</li>
            </ul>
        </div>
    </div>
	</p>


</body>
</html>