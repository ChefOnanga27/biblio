@extends('admin.admin')

@section('title', $article->exists ? 'Éditer l\'article' : 'Nouvel article')

@section('content')

<h1 class="text-3xl font-bold mb-8 text-gray-800">
    {{ $article->exists ? 'Éditer l\'article' : 'Créer un nouvel article' }}
</h1>

<form action="{{ $article->exists 
        ? route('admin.articles.update', $article) 
        : route('admin.articles.store') }}"
      method="POST"
      enctype="multipart/form-data"
      class="bg-white shadow rounded-lg p-8">

    @csrf
    @if($article->exists)
        @method('PUT')
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- IMAGE -->
    <div class="mb-6">
        <label for="image" class="block text-gray-700 font-bold mb-2">
            Image de couverture 
            @if(!$article->exists)
                <span class="text-red-500">*</span>
            @else
                <span class="text-gray-500 text-sm">(optionnel pour modification)</span>
            @endif
        </label>

        <input type="file" name="image" id="image"
               class="w-full border rounded px-3 py-2 @error('image') border-red-500 @enderror"
               accept="image/*"
               {{ !$article->exists ? 'required' : '' }}>

        @error('image')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror

        @if($article->image)
            <img src="{{ asset('storage/' . $article->image) }}"
                 class="w-32 h-32 object-cover mt-3 rounded">
            <p class="text-sm text-gray-600 mt-2">Image actuelle</p>
        @endif
    </div>

    <!-- TITRE -->
    <div class="mb-6">
        <label for="titre" class="block text-gray-700 font-bold mb-2">
            Titre
        </label>

        <input type="text" name="titre" id="titre"
               value="{{ old('titre', $article->titre) }}"
               class="w-full border rounded px-3 py-2 @error('titre') border-red-500 @enderror">
    </div>

    <!-- DESCRIPTION -->
    <div class="mb-6">
        <label for="description" class="block text-gray-700 font-bold mb-2">
            Description courte
        </label>

        <textarea name="description" id="description"
                  class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror"
                  rows="3">{{ old('description', $article->description) }}</textarea>
    </div>

    <!-- CONTENU -->
    <div class="mb-6">
        <label for="contenu" class="block text-gray-700 font-bold mb-2">
            Contenu
        </label>

        <textarea name="contenu" id="contenu"
                  class="w-full border rounded px-3 py-2 @error('contenu') border-red-500 @enderror"
                  rows="12">{{ old('contenu', $article->contenu) }}</textarea>
    </div>

    <!-- DATE DE PUBLICATION -->
    <div class="mb-6">
        <label for="published_at" class="block text-gray-700 font-bold mb-2">
            Date de publication (optionnel = brouillon)
        </label>

        <input type="datetime-local" name="published_at" id="published_at"
               value="{{ old('published_at', $article->published_at?->format('Y-m-d\TH:i')) }}"
               class="w-full border rounded px-3 py-2">
    </div>

    <!-- BUTTONS -->
    <div class="flex justify-end gap-4">
        <a href="{{ route('admin.articles.index') }}"
           class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700">
            Annuler
        </a>

        <button type="submit"
                class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
            {{ $article->exists ? 'Mettre à jour' : 'Créer' }}
        </button>
    </div>

</form>

@endsection
