<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'membres' => \App\Models\Member::where('statut', 'actif')->count(),
            'evenements' => \App\Models\Event::count(),
            'annees' => now()->year - 2010,
        ];

        $prochains_evenements = \App\Models\Event::aVenir()
            ->orderBy('date_event')
            ->take(3)
            ->get();

        $collections_actives = \App\Models\Collection::where('active', true)
            ->withCount('cotisations')
            ->take(3)
            ->get();

        return inertia('Home', compact('stats', 'prochains_evenements', 'collections_actives'));
    }
}
