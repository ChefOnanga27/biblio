@extends('base')

@section('title', $livre->titre)

@section('content')

{{-- Hero image --}}
<div class="relative w-full h-72 overflow-hidden bg-[#063537]">
    <img src="{{ asset('storage/' . $livre->image) }}"
         alt="{{ $livre->titre }}"
         class="w-full h-full object-cover opacity-40">
    <div class="absolute inset-0 bg-gradient-to-b from-[#063537]/30 to-[#063537]/85"></div>

    <span class="absolute top-6 left-6 inline-flex items-center gap-2
                 bg-white/10 border border-white/25 rounded-full
                 px-4 py-1.5 text-xs font-medium text-white/80 uppercase tracking-widest">
        <span class="w-1.5 h-1.5 rounded-full bg-orange-400"></span>
        Littérature gabonaise
    </span>
</div>

{{-- Card principale --}}
<div class="max-w-3xl mx-auto px-5 pb-16 -mt-20 relative z-10">
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
        <div class="flex">

            {{-- Accent latéral --}}
            <div class="w-1.5 bg-[#063537] flex-shrink-0"></div>

            {{-- Contenu --}}
            <div class="flex-1 px-10 py-9">

                <h1 class="font-serif text-3xl font-semibold text-gray-900 leading-snug mb-5">
                    {{ $livre->titre }}
                </h1>

                {{-- Métadonnées --}}
                <div class="flex flex-wrap gap-3 mb-6">
                    <span class="inline-flex items-center gap-2 text-sm text-gray-500
                                 bg-gray-50 border border-gray-100 rounded-full px-4 py-1.5">
                        <span class="text-xs">✍</span>
                        <span class="font-medium text-gray-800">{{ $livre->auteur }}</span>
                    </span>
                </div>

                <hr class="border-gray-100 mb-6">

                {{-- Description --}}
                <p class="text-gray-500 text-[15px] leading-relaxed font-light mb-8">
                    {{ $livre->description }}
                </p>

                {{-- Actions --}}
                <div class="flex flex-wrap items-center gap-3">
                    @if($livre->pdf_path)
                        <a href="{{ asset('storage/' . $livre->pdf_path) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 bg-[#063537] text-white
                                  text-sm font-medium px-6 py-2.5 rounded-full
                                  hover:bg-[#0a4f52] transition-colors">
                            <span class="text-xs bg-white/20 rounded px-1 py-0.5">↗</span>
                            Lire le livre (PDF)
                        </a>
                    @else
                        <span class="inline-flex items-center gap-2 bg-amber-50 text-amber-800
                                     text-sm font-medium px-5 py-2.5 rounded-full
                                     border border-amber-200">
                            PDF non disponible
                        </span>
                    @endif

                    <a href="{{ route('livres') }}"
                       class="inline-flex items-center gap-2 text-sm text-gray-500
                              border border-gray-200 rounded-full px-5 py-2.5
                              hover:bg-gray-50 transition-colors">
                        ← Retour à la bibliothèque
                    </a>
                </div>

                {{-- ISBN --}}
                <div class="mt-7 pt-5 border-t border-gray-100 flex items-center gap-4">
                    <span class="text-[11px] font-medium uppercase tracking-widest text-gray-400">
                        ISBN
                    </span>
                    <span class="text-sm text-gray-500 bg-gray-50 px-3 py-1 rounded-md tracking-wide">
                        {{ $livre->isbn }}
                    </span>
                </div>

            </div>
        </div>
    </div>

    {{-- Watermark Biblio --}}
    <p class="text-center mt-4 text-[11px] font-medium uppercase tracking-[0.25em] text-gray-300">
        B <span class="inline-block w-1 h-1 rounded-full bg-orange-400 mx-1 align-middle"></span>
        I <span class="inline-block w-1 h-1 rounded-full bg-orange-400 mx-1 align-middle"></span>
        B <span class="inline-block w-1 h-1 rounded-full bg-orange-400 mx-1 align-middle"></span>
        L <span class="inline-block w-1 h-1 rounded-full bg-orange-400 mx-1 align-middle"></span>
        I <span class="inline-block w-1 h-1 rounded-full bg-orange-400 mx-1 align-middle"></span>
        O <span class="inline-block w-1 h-1 rounded-full bg-orange-400 mx-1 align-middle"></span>
    </p>
</div>

@endsection