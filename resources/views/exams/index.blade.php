<x-full-layout>
	<h1>Lista Esami</h1>
	<div class="place-content-center">
		<table class="min-w-full rounded-lg">
			<thead>
				<tr class="bg-gray-100">
					<th>ssd</th>
					<th>Nome</th>
					<th>CFU</th>
				</tr>
			</thead>
			<tbody>
			@foreach($exams as $exam)
				<tr>
					<td>{{ $exam->ssd->code }}</td>
					<td>{{ $exam->name }}</td>
					<td class="text-center">{{ $exam->cfu }}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</x-full-layout>