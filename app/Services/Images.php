<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Enregistrement des images envoyées (couvertures, illustrations, avatars) sur le disque public.
 *
 * Si l'extension GD est disponible, l'image est redimensionnée et convertie en WebP
 * (fichiers bien plus légers). Sinon le fichier d'origine est conservé tel quel.
 */
class Images
{
    /** Formats prédéfinis : largeur max, hauteur max, recadrage carré. */
    public const FORMATS = [
        'couverture' => [600, 900, false],
        'illustration' => [1600, 1000, false],
        'avatar' => [400, 400, true],
    ];

    private const QUALITE = 82;

    public static function enregistrer(UploadedFile $fichier, string $dossier, string $format = 'couverture'): string
    {
        [$largeurMax, $hauteurMax, $carre] = self::FORMATS[$format];

        if (! self::gdDisponible() || ! ($source = @imagecreatefromstring((string) file_get_contents($fichier->getRealPath())))) {
            return $fichier->store($dossier, 'public');
        }

        $source = self::corrigerOrientation($source, $fichier);
        $image = $carre
            ? self::recadrerCarre($source, $largeurMax)
            : self::reduire($source, $largeurMax, $hauteurMax);

        ob_start();
        imagewebp($image, null, self::QUALITE);
        $contenu = ob_get_clean();
        imagedestroy($image);

        $chemin = trim($dossier, '/') . '/' . Str::uuid() . '.webp';
        Storage::disk('public')->put($chemin, $contenu);

        return $chemin;
    }

    /**
     * Remplace une image : enregistre la nouvelle puis supprime l'ancienne.
     */
    public static function remplacer(?string $ancienne, UploadedFile $fichier, string $dossier, string $format = 'couverture'): string
    {
        $chemin = self::enregistrer($fichier, $dossier, $format);
        self::supprimer($ancienne);

        return $chemin;
    }

    public static function supprimer(?string $chemin): void
    {
        if ($chemin) {
            Storage::disk('public')->delete($chemin);
        }
    }

    public static function gdDisponible(): bool
    {
        return function_exists('imagecreatefromstring') && function_exists('imagewebp');
    }

    private static function reduire(\GdImage $source, int $largeurMax, int $hauteurMax): \GdImage
    {
        $largeur = imagesx($source);
        $hauteur = imagesy($source);
        $ratio = min(1, $largeurMax / $largeur, $hauteurMax / $hauteur);

        if ($ratio >= 1) {
            return $source;
        }

        $image = imagescale($source, max(1, (int) round($largeur * $ratio)), max(1, (int) round($hauteur * $ratio)), IMG_BICUBIC);
        imagedestroy($source);

        return $image;
    }

    private static function recadrerCarre(\GdImage $source, int $cote): \GdImage
    {
        $taille = min(imagesx($source), imagesy($source));
        $image = imagecreatetruecolor($cote, $cote);
        imagecopyresampled(
            $image, $source, 0, 0,
            (int) ((imagesx($source) - $taille) / 2), (int) ((imagesy($source) - $taille) / 2),
            $cote, $cote, $taille, $taille
        );
        imagedestroy($source);

        return $image;
    }

    /** Photos de téléphone : applique la rotation indiquée dans les données EXIF. */
    private static function corrigerOrientation(\GdImage $image, UploadedFile $fichier): \GdImage
    {
        if (! function_exists('exif_read_data') || ! in_array($fichier->getMimeType(), ['image/jpeg', 'image/jpg'])) {
            return $image;
        }

        $orientation = @exif_read_data($fichier->getRealPath())['Orientation'] ?? 1;
        $angle = [3 => 180, 6 => -90, 8 => 90][$orientation] ?? 0;

        return $angle ? imagerotate($image, $angle, 0) : $image;
    }
}
