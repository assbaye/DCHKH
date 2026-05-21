<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GestionnaireMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $member = auth()->user()?->member;

        if (!$member || !in_array($member->role, ['admin', 'gestionnaire'])) {
            abort(403);
        }

        return $next($request);
    }
}
