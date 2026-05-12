<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $member = $request->user()?->member;
        if (!$member || !in_array($member->role, ['admin', 'moderateur'])) {
            abort(403, 'Accès réservé aux administrateurs.');
        }
        return $next($request);
    }
}
