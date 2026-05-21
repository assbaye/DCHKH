<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnlyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $member = $request->user()?->member;
        if (!$member || $member->role !== 'admin') {
            abort(403, 'Accès réservé aux administrateurs.');
        }
        return $next($request);
    }
}
