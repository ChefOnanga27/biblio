<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    | Disque des médias du site (couvertures, PDF, avatars, illustrations du blog) :
    | « public » (storage/app/public, en local), « neon » (Neon Object Storage) ou « supabase » (Supabase Storage).
    */

    'medias' => env('MEDIA_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

        // Neon Object Storage (bucket déclaré dans neon.ts) : `neon deploy` / `neon env pull` écrivent les
        // variables AWS_*. Le bucket est public_read : pas de visibility ici, Neon ne gère pas les ACL par objet.
        'neon' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_REGION', 'eu-central-1'),
            'bucket' => env('NEON_BUCKET', 'medias'),
            'endpoint' => env('AWS_ENDPOINT_URL_S3'),
            'url' => rtrim((string) env('AWS_ENDPOINT_URL_S3'), '/').'/'.env('NEON_BUCKET', 'medias'),
            'use_path_style_endpoint' => true,
            'throw' => true,
        ],

        // Supabase Storage via son API compatible S3 (Dashboard → Storage → S3 Connection).
        // Le bucket doit être « public » : les fichiers sont servis depuis /storage/v1/object/public/<bucket>/…
        'supabase' => [
            'driver' => 's3',
            'key' => env('SUPABASE_S3_KEY'),
            'secret' => env('SUPABASE_S3_SECRET'),
            'region' => env('SUPABASE_S3_REGION', 'eu-west-3'),
            'bucket' => env('SUPABASE_BUCKET', 'biblio'),
            'endpoint' => rtrim((string) env('SUPABASE_URL'), '/').'/storage/v1/s3',
            'url' => rtrim((string) env('SUPABASE_URL'), '/').'/storage/v1/object/public/'.env('SUPABASE_BUCKET', 'biblio'),
            'use_path_style_endpoint' => true,
            'visibility' => 'public',
            'throw' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
