<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Cotisation;
use App\Models\Emprunt;
use App\Models\Maintenance;
use App\Models\Materiel;
use App\Models\Member;
use App\Models\Reunion;

class DashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->member?->role;

        if ($role === 'secretaire') {
            return $this->dashboardSecretaire();
        }

        if ($role === 'tresorier') {
            return $this->dashboardTresorier();
        }

        if ($role === 'gestionnaire') {
            return $this->dashboardGestionnaire();
        }

        return $this->dashboardAdmin();
    }

    private function dashboardAdmin()
    {
        $stats = [
            'membres'            => Member::count(),
            'membres_actifs'     => Member::where('statut', 'actif')->count(),
            'evenements'         => \App\Models\Event::count(),
            'evenements_a_venir' => \App\Models\Event::where('date_event', '>=', now())->count(),
            'khassaides'         => \App\Models\Khassaide::count(),
            'photos'             => \App\Models\GalleryItem::where('type', 'photo')->count(),
            'total_collecte'     => Cotisation::sum('montant'),
            'collections_actives'=> Collection::where('active', true)->count(),
        ];

        $derniers_membres      = Member::orderBy('created_at', 'desc')->take(5)->get();
        $prochains_evenements  = \App\Models\Event::where('date_event', '>=', now())->orderBy('date_event')->take(5)->get();

        return inertia('Admin/Dashboard', compact('stats', 'derniers_membres', 'prochains_evenements'));
    }

    private function dashboardSecretaire()
    {
        $stats = [
            'reunions_total'    => Reunion::count(),
            'reunions_planifiees' => Reunion::where('statut', 'planifiee')->count(),
            'reunions_terminees'  => Reunion::where('statut', 'terminee')->count(),
            'membres_actifs'      => Member::where('statut', 'actif')->count(),
        ];

        $prochaines_reunions = Reunion::where('statut', 'planifiee')
            ->orderBy('date_reunion')
            ->take(5)
            ->get();

        $dernieres_reunions = Reunion::where('statut', 'terminee')
            ->orderBy('date_reunion', 'desc')
            ->take(5)
            ->get();

        return inertia('Admin/Dashboard', compact('stats', 'prochaines_reunions', 'dernieres_reunions'));
    }

    private function dashboardTresorier()
    {
        $debutMois = now()->startOfMonth();

        $stats = [
            'total_collecte'      => Cotisation::sum('montant'),
            'collecte_ce_mois'    => Cotisation::where('date_paiement', '>=', $debutMois)->sum('montant'),
            'nb_cotisants'        => Cotisation::distinct('member_id')->count('member_id'),
            'collections_actives' => Collection::where('active', true)->count(),
        ];

        $dernieres_cotisations = Cotisation::with('member')
            ->orderBy('date_paiement', 'desc')
            ->take(8)
            ->get();

        $collections_actives = Collection::where('active', true)
            ->orderBy('date_debut', 'desc')
            ->take(5)
            ->get();

        return inertia('Admin/Dashboard', compact('stats', 'dernieres_cotisations', 'collections_actives'));
    }

    private function dashboardGestionnaire()
    {
        $stats = [
            'total_materiels'   => Materiel::count(),
            'materiels_bon'     => Materiel::where('etat', 'bon')->count(),
            'emprunts_en_cours' => Emprunt::whereIn('statut', ['en_cours', 'en_retard'])->count(),
            'emprunts_en_retard'=> Emprunt::where('statut', 'en_retard')->count(),
            'maintenances_actives' => Maintenance::whereIn('statut', ['planifiee', 'en_cours'])->count(),
        ];

        $emprunts_en_cours = Emprunt::with(['materiel', 'membre'])
            ->whereIn('statut', ['en_cours', 'en_retard'])
            ->orderByRaw("FIELD(statut,'en_retard','en_cours')")
            ->orderBy('date_retour_prevue')
            ->take(6)
            ->get();

        $maintenances_actives = Maintenance::with('materiel')
            ->whereIn('statut', ['planifiee', 'en_cours'])
            ->orderBy('date_maintenance')
            ->take(5)
            ->get();

        return inertia('Admin/Dashboard', compact('stats', 'emprunts_en_cours', 'maintenances_actives'));
    }
}
