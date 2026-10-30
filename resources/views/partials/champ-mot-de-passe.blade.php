{{-- Champ mot de passe avec bouton « afficher ». Paramètres : $nom, $id, $placeholder (opt.), $autocomplete (opt.) --}}
<div class="relative" x-data="{ visible: false }">
    <input id="{{ $id }}" name="{{ $nom }}" :type="visible ? 'text' : 'password'" type="password" required
           placeholder="{{ $placeholder ?? 'Au moins 8 caractères' }}" autocomplete="{{ $autocomplete ?? 'current-password' }}"
           class="champ pr-12">
    <button type="button" @click="visible = ! visible" :aria-pressed="visible.toString()"
            class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center text-brume hover:text-encre cursor-pointer"
            :aria-label="visible ? 'Masquer le mot de passe' : 'Afficher le mot de passe'" aria-label="Afficher le mot de passe">
        <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
    </button>
</div>
