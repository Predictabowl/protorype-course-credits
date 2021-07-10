<x-full-layout>
	<h1>Esami Sostenuti</h1>
	<div class="place-content-center">
		<h2 class="text-xl font-semibold">{{ auth()->user()->name }}</h2>
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
					<td class="text-center">placeholder</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</x-full-layout>