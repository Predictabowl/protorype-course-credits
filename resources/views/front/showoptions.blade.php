<x-full-layout>
	<h1>Lista Esami</h1>
	<div class="place-content-center">
		<table class="min-w-full rounded-lg">
			<thead>
				<tr class="bg-gray-100">
					<th>id</th>
					<th>ssd</th>
					<th>Nome</th>
					<th>CFU</th>
				</tr>
			</thead>
			<tbody>
			@foreach($options as $option)
				<tr>
					<td>{{ $option->getId() }}</td>
					<td>{{ $option->getSsd() }}</td>
					<td>{{ $option->getExamName() }}</td>
					<td class="text-center">{{ $option->getCfu() }}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</x-full-layout>