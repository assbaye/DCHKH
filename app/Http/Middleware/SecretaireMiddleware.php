<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecretaireMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $member = $request->user()?->member;
        if (!$member || !in_array($member->role, ['admin', 'secretaire'])) {
            abort(403, 'Accès réservé aux secrétaires et administrateurs.');
        }
        return $next($request);
    }
}
