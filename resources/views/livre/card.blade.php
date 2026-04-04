<div class="group bg-white rounded-2xl border border-black/[0.07] overflow-hidden
            flex flex-col transition-all duration-200
            hover:-translate-y-1 hover:shadow-[0_12px_32px_rgba(6,53,55,0.10)]">

    {{-- Image --}}
    <div class="relative h-44 overflow-hidden bg-[#063537]">
        <img src="{{ asset('storage/' . $livre->image) }}"
             alt="{{ $livre->titre }}"
             class="w-full h-full object-cover opacity-90 transition-opacity duration-200 group-hover:opacity-100">

        <div class="absolute inset-x-0 bottom-0 h-14
                    bg-gradient-to-t from-[#063537]/55 to-transparent"></div>

        <span class="absolute top-2.5 right-2.5 text-[10px] font-medium uppercase
                     tracking-wide px-3 py-1 rounded-full
                     {{ $livre->disponible
                         ? 'bg-emerald-500/15 text-emerald-800 border border-emerald-400/40'
                         : 'bg-red-500/10 text-red-800 border border-red-400/30' }}">
            {{ $livre->disponible ? 'Disponible' : 'Indisponible' }}
        </span>
    </div>

    {{-- Accent --}}
    <div class="h-[3px] bg-[#063537]"></div>

    {{-- Corps --}}
    <div class="flex flex-col flex-1 p-[18px]">

        <h2 class="font-serif text-base font-semibold text-gray-900 leading-snug
                   line-clamp-2 mb-1.5">
            {{ $livre->titre }}
        </h2>

        <p class="flex items-center gap-1.5 text-[12px] text-gray-400 mb-2.5">
            <span class="w-[3px] h-[3px] rounded-full bg-orange-400 flex-shrink-0"></span>
            {{ $livre->auteur }}
        </p>

        <p class="text-[12.5px] text-gray-500 font-light leading-relaxed
                  line-clamp-3 mb-3 flex-1">
            {{ \Illuminate\Support\Str::limit($livre->description, 110) }}
        </p>

        {{-- Méta --}}
        <div class="bg-[#f9f7f4] rounded-xl px-3 py-2.5 space-y-1 mb-3.5">
            <p class="flex gap-2 text-[11px] text-gray-400">
                <span>ISBN</span>
                <span class="text-gray-600">{{ $livre->isbn }}</span>
            </p>
            <p class="flex gap-2 text-[11px] text-gray-400">
                <span>Publié le</span>
                <span class="text-gray-600">
                    {{ $livre->date_publication
                        ? \Carbon\Carbon::parse($livre->date_publication)->format('d/m/Y')
                        : 'N/A' }}
                </span>
            </p>
        </div>

        {{-- Bouton --}}
        @auth
            <a href="{{ route('livres.show', $livre) }}"
               class="block w-full text-center bg-orange-500 text-white text-[13px]
                      font-medium py-2.5 rounded-full hover:bg-orange-600 transition-colors">
                Voir les détails
            </a>
        @else
            <a href="{{ route('login') }}"
               class="block w-full text-center text-[13px] text-gray-500
                      border border-gray-200 py-2.5 rounded-full
                      hover:bg-gray-50 transition-colors">
                Connectez-vous pour lire
            </a>
        @endauth

    </div>
</div>