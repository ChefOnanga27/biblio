@extends('admin.admin')

@section('title', 'Articles du Blog')

@section('content')

<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-gray-800">
        📝 Articles du Blog
    </h1>

    <a href="{{ route('admin.articles.create') }}"
       class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
        + Nouvel Article
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-teal-600 text-white">
            <tr>
                <th class="p-4">Titre</th>
                <th class="p-4">Auteur</th>
                <th class="p-4">Status</th>
                <th class="p-4">Créé</th>
                <th class="p-4">Actions</th>
            </tr>
        </thead>

        <tbody class="divide-y">
            @forelse($articles as $article)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-semibold">{{ $article->titre }}</td>
                    <td class="p-4">{{ $article->user->name }}</td>
                    <td class="p-4">
                        @if($article->published_at)
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm">
                                ✅ Publié
                            </span>
                        @else
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded text-sm">
                                📝 Brouillon
                            </span>
                        @endif
                    </td>
                    <td class="p-4 text-sm text-gray-600">
                        {{ $article->created_at->format('d M Y') }}
                    </td>
                    <td class="p-4 flex gap-2">
                        <a href="{{ route('admin.articles.edit', $article) }}"
                           class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                            Éditer
                        </a>

                        <form action="{{ route('admin.articles.destroy', $article) }}"
                              method="POST"
                              style="display:inline"
                              onsubmit="return confirm('Êtes-vous sûr ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">
                        Aucun article. <a href="{{ route('admin.articles.create') }}" class="text-teal-600 underline">Créer un nouvel article</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- PAGINATION -->
<div class="mt-6">
    {{ $articles->links() }}
</div>

@endsection
