<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TresorierMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $member = $request->user()?->member;
        if (!$member || !in_array($member->role, ['admin', 'tresorier'])) {
            abort(403, 'Accès réservé aux trésoriers et administrateurs.');
        }
        return $next($request);
    }
}
