@extends('base')

@section('title', 'Contact - Biblio')

@section('content')

{{-- HERO --}}
<div class="relative bg-[#063537] overflow-hidden px-8 py-16">
    <div class="absolute -top-16 -right-12 w-80 h-80 rounded-full
                border border-orange-400/[0.12] pointer-events-none"></div>
    <div class="absolute -bottom-10 right-24 w-40 h-40 rounded-full
                border border-white/[0.05] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto relative">
        <div class="flex items-center gap-3 mb-5">
            <span class="w-7 h-0.5 bg-orange-400 rounded"></span>
            <span class="text-[11px] font-medium tracking-[0.12em] uppercase text-white/45">
                Nous contacter
            </span>
        </div>
        <h1 class="font-sans text-[2.6rem] font-semibold text-white leading-tight mb-3">
            Écrivez-<em class="italic text-orange-400">nous</em>
        </h1>
        <p class="text-[14.5px] text-white/50 font-light max-w-md leading-relaxed">
            Pour toute question, suggestion ou demande d'assistance —
            notre équipe vous répond dans les plus brefs délais.
        </p>
    </div>
</div>

{{-- LAYOUT --}}
<div class="max-w-4xl mx-auto px-5 py-11 grid grid-cols-1 lg:grid-cols-[1fr_280px]
            gap-6 items-start">

    {{-- FORMULAIRE --}}
    <div class="bg-white rounded-[20px] border border-black/[0.07] overflow-hidden">

        {{-- Barre dégradée --}}
        <div class="h-1" style="background: linear-gradient(to right, #063537, #F97316)"></div>

        <div class="px-10 py-9">
            <h2 class="font-sans text-[1.3rem] font-semibold text-gray-900 mb-1">
                Votre message
            </h2>
            <p class="text-[13px] text-gray-400 font-light mb-7">
                Tous les champs sont requis.
            </p>

            @if(session('success'))
                <div class="mb-6 flex items-center gap-3 bg-emerald-50 border
                            border-emerald-200 rounded-xl px-4 py-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0"></span>
                    <p class="text-[13px] text-emerald-700">{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="name"
                           class="block text-[11px] font-medium tracking-[0.05em]
                                  uppercase text-gray-400 mb-2">
                        Nom complet
                    </label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name') }}" required
                           placeholder="Ex : Jean-Baptiste Mba"
                           class="w-full bg-[#f9f7f4] border border-black/10 rounded-xl
                                  px-4 py-3 text-[14px] text-gray-800 font-light
                                  placeholder:text-gray-300
                                  focus:outline-none focus:border-[#063537]/40 focus:bg-white
                                  transition-colors">
                    @error('name')
                        <p class="text-[11px] text-red-400 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email"
                           class="block text-[11px] font-medium tracking-[0.05em]
                                  uppercase text-gray-400 mb-2">
                        Adresse e-mail
                    </label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}" required
                           placeholder="moimm@exemple.com"
                           class="w-full bg-[#f9f7f4] border border-black/10 rounded-xl
                                  px-4 py-3 text-[14px] text-gray-800 font-light
                                  placeholder:text-gray-300
                                  focus:outline-none focus:border-[#063537]/40 focus:bg-white
                                  transition-colors">
                    @error('email')
                        <p class="text-[11px] text-red-400 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message"
                           class="block text-[11px] font-medium tracking-[0.05em]
                                  uppercase text-gray-400 mb-2">
                        Message
                    </label>
                    <textarea id="message" name="message" rows="5" required
                              placeholder="Décrivez votre demande..."
                              class="w-full bg-[#f9f7f4] border border-black/10 rounded-xl
                                     px-4 py-3 text-[14px] text-gray-800 font-light
                                     placeholder:text-gray-300 resize-none leading-relaxed
                                     focus:outline-none focus:border-[#063537]/40 focus:bg-white
                                     transition-colors">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-[11px] text-red-400 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="group w-full bg-[#063537] hover:bg-[#0a4f52] text-white
                               text-[14px] font-medium py-3.5 rounded-full
                               flex items-center justify-center gap-2.5
                               transition-colors mt-2">
                    Envoyer le message
                    <span class="transition-transform group-hover:translate-x-1">→</span>
                </button>
            </form>
        </div>
    </div>

    {{-- SIDEBAR --}}
    <div class="flex flex-col gap-4">

        {{-- Coordonnées --}}
        <div class="bg-white rounded-2xl border border-black/[0.07] overflow-hidden">
            <div class="bg-[#063537] px-5 py-3.5 flex items-center gap-2.5">
                <svg class="w-[18px] h-[18px] opacity-60" fill="none"
                     stroke="white" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                </svg>
                <span class="font-sans text-[14px] font-semibold text-white">
                    Nos coordonnées
                </span>
            </div>

            <div class="p-4 flex flex-col gap-3.5">
                @foreach([
                    ['E-mail', 'contact@biblio.ga', 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75'],
                    ['Adresse', 'Libreville, Gabon', 'M15 10.5a3 3 0 11-6 0 3 3 0 016 0zM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z'],
                    ['Téléphone', '+241 00 00 00 00', 'M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z'],
                ] as [$label, $value, $path])
                <div class="flex items-start gap-3">
                    <div class="w-[34px] h-[34px] rounded-[9px] bg-[#f4f1ec]
                                flex items-center justify-center flex-shrink-0">
                        <svg class="w-[15px] h-[15px]" fill="none"
                             stroke="#063537" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="{{ $path }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-medium tracking-[0.07em] uppercase
                                  text-gray-300 mb-0.5">{{ $label }}</p>
                        <p class="text-[13px] text-gray-600">{{ $value }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Horaires --}}
        <div class="relative bg-[#063537] rounded-2xl px-5 py-5 overflow-hidden">
            <div class="absolute -top-8 -right-8 w-28 h-28 rounded-full
                        border border-orange-400/15 pointer-events-none"></div>
            <p class="font-sans text-[14px] font-semibold text-white mb-3.5
                      flex items-center gap-2">
                <span class="w-[5px] h-[5px] rounded-full bg-orange-400"></span>
                Horaires de support
            </p>
            @foreach([['Lun – Ven','8h – 18h'],['Samedi','9h – 13h'],['Dimanche','Fermé']] as [$j,$h])
            <div class="flex justify-between py-2 text-[12px]
                        {{ !$loop->last ? 'border-b border-white/[0.07]' : '' }}">
                <span class="text-white/50 font-light">{{ $j }}</span>
                <span class="text-white/80">{{ $h }}</span>
            </div>
            @endforeach
        </div>

    </div>

    {{-- Watermark --}}
    <p class="text-center mt-6 text-[10px] font-medium tracking-[0.25em]
              uppercase text-gray-300 lg:col-span-2">
        B<span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
        I <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
        B <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
        L <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
        I <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
        O <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
        <span class="mx-2">·</span>
    </p>

</div>

@endsection