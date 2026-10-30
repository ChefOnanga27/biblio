@extends('layouts.auth')

@section('title', 'Vérification — ' . $siteNom)
@section('centre', true)
@section('auth-title', 'Vérification')
@section('auth-subtitle')
    Un code à 5 chiffres a été envoyé à <span class="text-encre font-medium">{{ auth()->user()->email }}</span>
@endsection

@section('auth')
@if (session('status') === 'verification-link-sent' && ! session('error'))
    <p role="status" class="mb-6 rounded-lg bg-menthe/10 border border-menthe/25 text-green-200 text-[13.5px] px-4 py-3">
        Un nouveau code vient de vous être envoyé.
    </p>
@endif

<form method="POST" action="{{ route('verification.code') }}" x-data="codeOtp()">
    @csrf
    <p id="code-aide" class="text-[14.5px] text-brume">Saisissez votre code ici</p>

    <div class="mt-7 flex justify-center gap-3 sm:gap-5" role="group" aria-labelledby="code-aide">
        @for ($i = 0; $i < 5; $i++)
            <input type="text" name="code[]" inputmode="numeric" pattern="[0-9]" maxlength="1" required
                   autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}" aria-label="Chiffre {{ $i + 1 }}"
                   @if($i === 0) autofocus @endif
                   x-ref="c{{ $i }}" @input="saisie($event, {{ $i }})" @keydown.backspace="retour($event, {{ $i }})" @paste.prevent="coller($event)"
                   class="w-12 sm:w-14 h-14 bg-transparent border-0 border-b-[3px] border-[#3a4258] focus:border-corail
                          text-center text-[26px] font-semibold text-encre outline-none transition-colors">
        @endfor
    </div>
    @include('partials.erreur', ['champ' => 'code'])

    <button type="submit" class="btn btn-blanc w-full !py-4 mt-10">Vérifier</button>
</form>

<form method="POST" action="{{ route('verification.send') }}" class="mt-7 text-[14.5px] text-brume">
    @csrf
    Rien reçu ?
    <button type="submit" class="font-semibold text-encre hover:text-corail cursor-pointer">Renvoyer un code</button>
</form>

<form method="POST" action="{{ route('logout') }}" class="mt-3">
    @csrf
    <button type="submit" class="text-[13px] text-voile hover:text-brume cursor-pointer">Se déconnecter</button>
</form>

<script>
    function codeOtp() {
        return {
            cases() { return [0, 1, 2, 3, 4].map(i => this.$refs['c' + i]); },
            saisie(e, i) {
                e.target.value = e.target.value.replace(/\D/g, '').slice(-1);
                if (e.target.value && i < 4) this.$refs['c' + (i + 1)].focus();
                if (this.cases().every(c => c.value)) this.$root.requestSubmit();
            },
            retour(e, i) {
                if (!e.target.value && i > 0) this.$refs['c' + (i - 1)].focus();
            },
            coller(e) {
                const chiffres = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 5).split('');
                this.cases().forEach((c, i) => c.value = chiffres[i] || '');
                (this.cases().find(c => !c.value) || this.cases()[4]).focus();
                if (chiffres.length === 5) this.$root.requestSubmit();
            },
        };
    }
</script>
@endsection
