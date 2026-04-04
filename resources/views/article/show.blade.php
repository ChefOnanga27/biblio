@extends('base')

@section('title', $article->titre . ' - Blog')

@section('content')

<div class="max-w-6xl mx-auto px-4 py-10">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Article Principal -->
        <article class="lg:col-span-2">

            @if($article->image)
                <img src="{{ asset('storage/' . $article->image) }}" 
                     alt="{{ $article->titre }}"
                     class="w-full h-96 object-cover rounded-lg mb-8">
            @endif

            <div class="bg-white rounded-lg shadow-lg p-8">

                <h1 class="text-4xl font-bold mb-4 text-gray-800">
                    {{ $article->titre }}
                </h1>

                <div class="flex flex-wrap items-center gap-4 text-gray-600 mb-8 pb-8 border-b">
                    <span>✍️ {{ $article->user->name }}</span>
                    <span>📅 {{ $article->published_at?->format('d F Y') }}</span>
                </div>

                <div class="prose prose-lg max-w-none mb-8">
                    {!! nl2br(e($article->contenu)) !!}
                </div>

                <div class="mt-10 pt-8 border-t">
                    <a href="{{ route('articles.index') }}" 
                       class="inline-block bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700">
                        ← Retour au blog
                    </a>
                </div>

            </div>

        </article>

        <!-- Sidebar - Autres Articles -->
        <aside class="lg:col-span-1">

            <div class="sticky top-4 bg-white rounded-lg shadow-lg p-6">

                <h3 class="text-xl font-bold text-gray-800 mb-6 pb-4 border-b">
                    📚 Autres articles
                </h3>

                <div class="space-y-4">
                    @forelse($otherArticles as $other)
                        <a href="{{ route('articles.show', $other) }}"
                           class="block p-3 rounded-lg hover:bg-gray-100 transition group">

                            <div class="flex gap-3">
                                @if($other->image)
                                    <img src="{{ asset('storage/' . $other->image) }}"
                                         alt="{{ $other->titre }}"
                                         class="w-16 h-16 object-cover rounded">
                                @else
                                    <div class="w-16 h-16 bg-gradient-to-r from-teal-500 to-blue-500 rounded flex items-center justify-center">
                                        <span class="text-white text-lg">📄</span>
                                    </div>
                                @endif

                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-800 group-hover:text-teal-600 line-clamp-2 text-sm">
                                        {{ $other->titre }}
                                    </h4>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $other->published_at?->format('d M Y') }}
                                    </p>
                                </div>
                            </div>

                        </a>
                    @empty
                        <p class="text-gray-500 text-center text-sm">
                            Pas d'autres articles pour le moment.
                        </p>
                    @endforelse
                </div>

            </div>

        </aside>

    </div>

</div>

@endsection
