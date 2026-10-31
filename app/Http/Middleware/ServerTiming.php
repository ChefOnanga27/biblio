<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ServerTiming
{
    /**
     * En-tête Server-Timing (onglet Réseau du navigateur) : temps passé en base et temps total côté serveur.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sql = 0.0;
        $requetes = 0;
        DB::listen(function ($query) use (&$sql, &$requetes) {
            $sql += $query->time;
            $requetes++;
        });

        $response = $next($request);

        $total = (microtime(true) - (defined('LARAVEL_START') ? LARAVEL_START : $request->server('REQUEST_TIME_FLOAT'))) * 1000;
        $response->headers->set('Server-Timing', sprintf(
            'sql;dur=%.1f;desc="%d requetes", app;dur=%.1f',
            $sql, $requetes, $total
        ));

        return $response;
    }
}
