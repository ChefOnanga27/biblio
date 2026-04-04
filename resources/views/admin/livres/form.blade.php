@extends('admin.admin')

@section('title', $livre->exists ? 'Éditer le livre' : 'Ajouter un livre')

@section('content')

<h1 class="text-2xl font-bold mb-6">
    {{ $livre->exists ? 'Éditer le livre' : 'Ajouter un livre' }}
</h1>

<form action="{{ $livre->exists
        ? route('admin.livres.update', $livre)
        : route('admin.livres.store') }}"
      method="POST"
      enctype="multipart/form-data"
      class="bg-white shadow rounded-lg p-6">

    @csrf

    {{-- METHOD PUT si édition --}}
    @if($livre->exists)
        @method('PUT')
    @endif

    <!-- IMAGE -->
    <div class="mb-4">
        <label for="image" class="block text-gray-700 font-bold mb-2">
            Image du livre
        </label>

        <input type="file" name="image" id="image"
               class="w-full border rounded px-3 py-2">

        @if($livre->image)
            <img src="{{ asset('storage/' . $livre->image) }}"
                 class="w-20 h-20 object-cover mt-2 rounded">
        @endif
    </div>

    <!-- PDF -->
    <div class="mb-4">
        <label for="pdf" class="block text-gray-700 font-bold mb-2">
            Fichier PDF
        </label>

        <input type="file" name="pdf" id="pdf"
               class="w-full border rounded px-3 py-2"
               accept="application/pdf">

        @if($livre->pdf_path)
            <p class="text-sm text-gray-600 mt-2">
                Fichier actuel : <a href="{{ asset('storage/' . $livre->pdf_path) }}" target="_blank" class="text-blue-600 underline">Voir / Télécharger</a>
            </p>
        @endif
    </div>

    <!-- TITRE -->
    <div class="mb-4">
        <label for="titre" class="block text-gray-700 font-bold mb-2">
            Titre
        </label>

        <input type="text" name="titre" id="titre"
               value="{{ old('titre', $livre->titre) }}"
               class="w-full border rounded px-3 py-2">
    </div>

    <!-- AUTEUR -->
    <div class="mb-4">
        <label for="auteur" class="block text-gray-700 font-bold mb-2">
            Auteur
        </label>

        <input type="text" name="auteur" id="auteur"
               value="{{ old('auteur', $livre->auteur) }}"
               class="w-full border rounded px-3 py-2">
    </div>

    <!-- CATEGORIE -->
    <div class="mb-4">
        <label for="categorie_id" class="block text-gray-700 font-bold mb-2">
            Catégorie
        </label>

        <select name="categorie_id" id="categorie_id" class="w-full border rounded px-3 py-2">
            <option value="">-- Choisir une catégorie --</option>
            @foreach($categories as $categorie)
                <option value="{{ $categorie->id }}" {{ old('categorie_id', $livre->categorie_id) == $categorie->id ? 'selected' : '' }}>
                    {{ $categorie->nom }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- ISBN -->
    <div class="mb-4">
        <label for="isbn" class="block text-gray-700 font-bold mb-2">
            ISBN
        </label>

        <input type="text" name="isbn" id="isbn"
               value="{{ old('isbn', $livre->isbn) }}"
               class="w-full border rounded px-3 py-2">
    </div>

    <!-- DESCRIPTION -->
    <div class="mb-4">
        <label for="description" class="block text-gray-700 font-bold mb-2">
            Description
        </label>

        <textarea name="description" id="description"
                  class="w-full border rounded px-3 py-2">{{ old('description', $livre->description) }}</textarea>
    </div>

    <!-- DATE -->
    <div class="mb-4">
        <label for="date_publication" class="block text-gray-700 font-bold mb-2">
            Date de publication
        </label>

        <input type="date" name="date_publication" id="date_publication"
               value="{{ old('date_publication', $livre->date_publication ?? '') }}"
               class="w-full border rounded px-3 py-2">
    </div>

    <!-- DISPONIBLE -->
    <div class="mb-4">
        <label class="inline-flex items-center">
            <input type="checkbox" name="disponible" id="disponible"
                   class="form-checkbox h-5 w-5 text-blue-600"
                   {{ old('disponible', $livre->disponible ?? false) ? 'checked' : '' }}>
            <span class="ml-2 text-gray-700 font-bold">Disponible</span>
        </label>
    </div>

    <!-- BUTTONS -->
    <div class="flex justify-end">
        <a href="{{ route('admin.livres.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 mr-2">
            Annuler
        </a>

        <button type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Enregistrer
        </button>
    </div>

</form>

@endsection