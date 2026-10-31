<?php

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

if (! function_exists('medias')) {
    /**
     * Disque des médias du site (config filesystems.medias : « public », « neon » ou « supabase »).
     */
    function medias(): Filesystem
    {
        return Storage::disk(config('filesystems.medias'));
    }
}

if (! function_exists('media_url')) {
    /**
     * URL publique d'un média (couverture, PDF, avatar…), ou null s'il n'y en a pas.
     */
    function media_url(?string $chemin): ?string
    {
        if (! $chemin) {
            return null;
        }

        // En local, on garde l'hôte de la requête (APP_URL peut différer de l'adresse utilisée)
        if (config('filesystems.medias') === 'public') {
            return asset('storage/' . $chemin);
        }

        return medias()->url($chemin);
    }
}
