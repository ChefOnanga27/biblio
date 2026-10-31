<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

/**
 * Transfert des données et des fichiers locaux vers Supabase, en trois étapes :
 *
 *   php artisan supabase:transferer fichiers   storage/app/public → Supabase Storage
 *   php artisan supabase:transferer exporter   base locale (MySQL) → fichier JSON
 *   php artisan supabase:transferer importer   fichier JSON → base Supabase (PostgreSQL, extension pdo_pgsql requise)
 */
class TransfertSupabase extends Command
{
    protected $signature = 'supabase:transferer
                            {etape : fichiers, exporter ou importer}
                            {--depuis= : connexion source pour « exporter » (par défaut : la connexion courante)}
                            {--force : écraser les fichiers existants / vider les tables Supabase avant l\'import}
                            {--garder : conserver le fichier d\'export après un import réussi}';

    protected $description = 'Transfère les fichiers et les données locales vers Supabase';

    /** Tables à transférer, dans l'ordre des clés étrangères. */
    private const TABLES = [
        'users', 'categories', 'genres', 'auteurs', 'livres', 'articles',
        'lectures', 'visitors', 'favoris', 'notes', 'contributions',
        'password_reset_tokens', 'personal_access_tokens',
    ];

    private const EXPORT = 'transfert-supabase.json';

    public function handle(): int
    {
        return match ($this->argument('etape')) {
            'fichiers' => $this->fichiers(),
            'exporter' => $this->exporter(),
            'importer' => $this->importer(),
            default => $this->echec('Étape inconnue : utilisez « fichiers », « exporter » ou « importer ».'),
        };
    }

    private function fichiers(): int
    {
        $source = Storage::disk('public');
        $cible = Storage::disk('supabase');
        $fichiers = collect($source->allFiles())->reject(fn ($f) => str_starts_with(basename($f), '.'));

        if ($fichiers->isEmpty()) {
            $this->info('Aucun fichier à transférer dans storage/app/public.');
            return self::SUCCESS;
        }

        $envoyes = $ignores = 0;
        $this->withProgressBar($fichiers, function ($chemin) use ($source, $cible, &$envoyes, &$ignores) {
            if (! $this->option('force') && $cible->exists($chemin)) {
                $ignores++;
                return;
            }
            $cible->writeStream($chemin, $source->readStream($chemin), [
                'visibility' => 'public',
                'ContentType' => $source->mimeType($chemin) ?: 'application/octet-stream',
            ]);
            $envoyes++;
        });

        $this->newLine(2);
        $this->info("Fichiers envoyés : {$envoyes} · déjà présents (ignorés) : {$ignores}");

        return self::SUCCESS;
    }

    private function exporter(): int
    {
        $connexion = $this->option('depuis') ?: config('database.default');
        $base = DB::connection($connexion);
        $export = ['connexion' => $connexion, 'date' => now()->toIso8601String(), 'tables' => []];

        foreach (self::TABLES as $table) {
            if (! Schema::connection($connexion)->hasTable($table)) {
                continue;
            }
            $ordre = Schema::connection($connexion)->hasColumn($table, 'id') ? 'id' : 'email';
            $export['tables'][$table] = $base->table($table)->orderBy($ordre)->get()->map(fn ($l) => (array) $l)->all();
            $this->line(sprintf('  %-24s %d ligne(s)', $table, count($export['tables'][$table])));
        }

        Storage::disk('local')->put(self::EXPORT, json_encode($export, JSON_UNESCAPED_UNICODE));
        $this->newLine();
        $this->info('Export écrit dans storage/app/' . self::EXPORT);
        $this->warn('Ce fichier contient des emails et des mots de passe chiffrés : ne le partagez pas, il sera supprimé après l\'import.');

        return self::SUCCESS;
    }

    private function importer(): int
    {
        if (! Storage::disk('local')->exists(self::EXPORT)) {
            return $this->echec('Fichier storage/app/' . self::EXPORT . ' introuvable : lancez d\'abord l\'étape « exporter ».');
        }

        $connexion = 'supabase';
        $schema = Schema::connection($connexion);

        if (! $schema->hasTable('users') || ! $schema->hasTable('contributions')) {
            return $this->echec('Les tables n\'existent pas encore sur Supabase : lancez « php artisan migrate --database=supabase ».');
        }

        $tables = json_decode(Storage::disk('local')->get(self::EXPORT), true)['tables'];
        $base = DB::connection($connexion);

        $remplies = collect(array_keys($tables))->filter(fn ($t) => $schema->hasTable($t) && $base->table($t)->exists());
        if ($remplies->isNotEmpty() && ! $this->option('force')) {
            return $this->echec('Ces tables Supabase contiennent déjà des données : ' . $remplies->join(', ')
                . '. Relancez avec --force pour les vider avant l\'import.');
        }

        $base->transaction(function () use ($tables, $base, $schema, $connexion, $remplies) {
            if ($remplies->isNotEmpty()) {
                $base->statement('TRUNCATE ' . collect(array_keys($tables))->map(fn ($t) => '"' . $t . '"')->join(', ') . ' RESTART IDENTITY CASCADE');
            }

            foreach ($tables as $table => $lignes) {
                if (! $schema->hasTable($table)) {
                    continue;
                }

                // MySQL stocke les booléens en 0/1 : PostgreSQL attend de vrais booléens
                $booleens = collect($schema->getColumns($table))->filter(fn ($c) => $c['type_name'] === 'bool')->pluck('name')->all();
                $lignes = array_map(function ($ligne) use ($booleens) {
                    foreach ($booleens as $colonne) {
                        if (array_key_exists($colonne, $ligne) && $ligne[$colonne] !== null) {
                            $ligne[$colonne] = (bool) $ligne[$colonne];
                        }
                    }
                    return $ligne;
                }, $lignes);

                foreach (array_chunk($lignes, 200) as $paquet) {
                    $base->table($table)->insert($paquet);
                }

                // Les identifiants ont été copiés tels quels : on recale le compteur auto-incrémenté
                if ($schema->hasColumn($table, 'id')) {
                    $base->statement("SELECT setval(pg_get_serial_sequence('\"{$table}\"', 'id'), COALESCE(MAX(id), 1), MAX(id) IS NOT NULL) FROM \"{$table}\"");
                }

                $this->line(sprintf('  %-24s %d ligne(s)', $table, count($lignes)));
            }
        });

        if (! $this->option('garder')) {
            Storage::disk('local')->delete(self::EXPORT);
        }

        $this->newLine();
        $this->info('Import terminé dans Supabase.');

        return self::SUCCESS;
    }

    private function echec(string $message): int
    {
        $this->error($message);

        return self::FAILURE;
    }
}
