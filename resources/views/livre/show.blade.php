@extends('base')

@section('title', $livre->titre)

@section('content')

<div class="max-w-4xl mx-auto p-6">

    <div class="bg-white shadow-lg rounded-xl p-6">

        <img src="{{ asset('storage/' . $livre->image) }}" 
             class="w-full h-80 object-cover rounded-lg mb-6">

        <h1 class="text-3xl font-bold mb-4">
            {{ $livre->titre }}
        </h1>

        <p class="text-gray-600 mb-2">
            ✍️ Auteur : {{ $livre->auteur }}
        </p>

        <p class="text-gray-600 mb-4">
            📚 ISBN : {{ $livre->isbn }}
        </p>

        <p class="text-gray-700 mb-6">
            {{ $livre->description }}
        </p>

        @if($livre->pdf_path)
            <a href="{{ asset('storage/' . $livre->pdf_path) }}" target="_blank" rel="noopener"
               class="inline-block bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 mr-2">
                📖 Lire le livre (PDF)
            </a>
        @else
            <span class="inline-block bg-yellow-400 text-gray-800 px-6 py-2 rounded-full mr-2">
                PDF non disponible
            </span>
        @endif

        <a href="{{ route('livres') }}" 
           class="inline-block bg-gray-800 text-white px-6 py-2 rounded-full hover:bg-gray-700">
            ← Retour
        </a>

    </div>

</div>

@endsection