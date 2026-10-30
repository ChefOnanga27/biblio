{{-- Message d'erreur de validation d'un champ. Paramètre : $champ --}}
@error($champ)
    <p class="mt-2 text-[13px] text-[#ff8a73]" role="alert">{{ $message }}</p>
@enderror
