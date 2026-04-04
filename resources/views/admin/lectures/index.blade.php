@extends('admin.admin')

@section('title', 'Historique des lectures')

@section('content')

<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold">📖 Historique des lectures</h1>
</div>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-orange-500 text-white">
            <tr>
                <th class="p-3">Date de lecture</th>
                <th class="p-3">Utilisateur</th>
                <th class="p-3">Livre</th>
                <th class="p-3">ISBN</th>
                <th class="p-3">Pages</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($lectures as $lecture)
                <tr class="hover:bg-gray-50">
                    <td class="p-3">{{ $lecture->read_at->format('d/m/Y H:i') }}</td>
                    <td class="p-3">{{ $lecture->user->name }} ({{ $lecture->user->email }})</td>
                    <td class="p-3">{{ $lecture->livre->titre }}</td>
                    <td class="p-3">{{ $lecture->livre->isbn }}</td>
                    <td class="p-3">{{ $lecture->livre->pages ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">Aucune lecture enregistrée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $lectures->links() }}</div>

@endsection