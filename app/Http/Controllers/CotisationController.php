<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;

class CotisationController extends Controller
{
    public function index()
    {
        $collections = Collection::where('active', true)
            ->withSum('cotisations', 'montant')
            ->orderBy('date_debut', 'desc')
            ->get()
            ->map(fn($c) => array_merge($c->toArray(), ['pourcentage' => $c->pourcentage]));

        $historique = Collection::where('active', false)
            ->withSum('cotisations', 'montant')
            ->orderBy('date_debut', 'desc')
            ->take(5)
            ->get();

        return inertia('Cotisations/Index', compact('collections', 'historique'));
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
