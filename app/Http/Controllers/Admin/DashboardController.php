<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'membres' => \App\Models\Member::count(),
            'membres_actifs' => \App\Models\Member::where('statut', 'actif')->count(),
            'evenements' => \App\Models\Event::count(),
            'evenements_a_venir' => \App\Models\Event::where('date_event', '>=', now())->count(),
            'khassaides' => \App\Models\Khassaide::count(),
            'photos' => \App\Models\GalleryItem::where('type', 'photo')->count(),
            'total_collecte' => \App\Models\Cotisation::sum('montant'),
            'collections_actives' => \App\Models\Collection::where('active', true)->count(),
        ];

        $derniers_membres = \App\Models\Member::orderBy('created_at', 'desc')->take(5)->get();
        $prochains_evenements = \App\Models\Event::where('date_event', '>=', now())->orderBy('date_event')->take(5)->get();

        return inertia('Admin/Dashboard', compact('stats', 'derniers_membres', 'prochains_evenements'));
    }
}
