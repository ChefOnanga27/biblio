@extends('base')

@section('title', 'Blog - Biblio')

@section('content')

<div class="bg-gradient-to-r from-teal-700 to-gray-400 text-gray-800 p-10 mb-10">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-4xl font-bold mb-2">Le Blog de Biblio 📝</h1>
        <p class="text-lg">
            Renseignez-vous sur l'auto-édition, l'écriture, l'édition, la publication et la mise en avant de votre livre.
        </p>
    </div>
</div>

<section class="max-w-7xl mx-auto px-4 py-10">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($articles as $article)
            <article class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition">
                @if($article->image)
                    <img src="{{ asset('storage/' . $article->image) }}" 
                         alt="{{ $article->titre }}"
                         class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gradient-to-r from-teal-500 to-blue-500 flex items-center justify-center">
                        <span class="text-white text-4xl">📚</span>
                    </div>
                @endif

                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2 text-gray-800">
                        {{ $article->titre }}
                    </h3>

                    <p class="text-sm text-gray-500 mb-3">
                        ✍️ Par {{ $article->user->name }} • {{ $article->published_at?->format('d M Y') }}
                    </p>

                    <p class="text-gray-600 mb-4 line-clamp-3">
                        {{ $article->description }}
                    </p>

                    <a href="{{ route('articles.show', $article) }}" 
                       class="inline-block bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700 transition">
                        Lire la suite →
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full text-center py-10">
                <p class="text-gray-500 text-lg">Aucun article publié pour le moment.</p>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    <div class="mt-10">
        {{ $articles->links() }}
    </div>

</section>

@endsection
