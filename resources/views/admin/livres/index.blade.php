@extends('admin.admin')

@section('title', 'Liste des livres')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">📚 Liste des Livres</h1>

    <a href="{{ route('admin.livres.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Ajouter un livre
    </a>
</div>

<div class="bg-white shadow rounded-lg overflow-hidden">

    <table class="w-full text-left">
        <thead class="bg-orange-500 text-white">
            <tr>
                <th class="p-3">ID</th>
                <th class="p-3">Image</th>
                <th class="p-3">Titre</th>
                <th class="p-3">Auteur</th>
                <th class="p-3">ISBN</th>
                <th class="p-3">Description</th>
                <th class="p-3">Date</th>
                <th class="p-3">Statut</th>
                <th class="p-3">Actions</th>
            </tr>
        </thead>

        <tbody class="divide-y">

        @forelse($livres as $livre)
            <tr class="hover:bg-gray-50">

                <td class="p-3">{{ $livre->id }}</td>

                <td class="p-3">
                    @if($livre->image)
                        <img src="{{ asset('storage/' . $livre->image) }}"
                             class="w-14 h-14 object-cover rounded">
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </td>

                <td class="p-3 font-semibold">{{ $livre->titre }}</td>
                <td class="p-3">{{ $livre->auteur }}</td>
                <td class="p-3">{{ $livre->isbn }}</td>

                <td class="p-3 text-gray-600">
                    {{ \Illuminate\Support\Str::limit($livre->description, 40) }}
                </td>

                <td class="p-3">
                    {{ $livre->date_publication ? \Carbon\Carbon::parse($livre->date_publication)->format('d/m/Y') : 'N/A' }}
                </td>

                <td class="p-3">
                    @if($livre->disponible)
                        <span class="px-2 py-1 text-sm bg-green-100 text-green-700 rounded">
                            Disponible
                        </span>
                    @else
                        <span class="px-2 py-1 text-sm bg-red-100 text-red-700 rounded">
                            Indisponible
                        </span>
                    @endif
                </td>

                <td class="p-3 flex gap-2">

                    <a href="{{ route('admin.livres.edit', $livre) }}"
                       class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                        Modifier
                    </a>

                    <form action="{{ route('admin.livres.destroy', $livre) }}" method="POST">
    @csrf
    @method('DELETE')

    <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
        Supprimer
    </button>
</form>

                </td>

            </tr>
        @empty
            <tr>
                <td colspan="9" class="p-4 text-center text-gray-500">
                    Aucun livre trouvé
                </td>
            </tr>
        @endforelse

        </tbody>
    </table>
</div>

@endsection