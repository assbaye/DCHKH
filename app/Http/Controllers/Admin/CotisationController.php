<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cotisation;
use App\Models\Member;
use App\Models\Collection;
use Illuminate\Http\Request;

class CotisationController extends Controller
{
    public function index()
    {
        $cotisations = Cotisation::with(['member', 'collection'])
            ->orderBy('date_paiement', 'desc')
            ->paginate(20);
        return inertia('Admin/Cotisations/Index', ['cotisations' => $cotisations]);
    }

    public function create()
    {
        return inertia('Admin/Cotisations/Form', [
            'membres'     => Member::where('statut', 'actif')->orderBy('nom')->get(['id', 'nom', 'prenom']),
            'collections' => Collection::where('active', true)->orderBy('titre')->get(['id', 'titre']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id'     => 'required|exists:members,id',
            'collection_id' => 'nullable|exists:collections,id',
            'montant'       => 'required|numeric|min:1',
            'date_paiement' => 'required|date',
            'mode_paiement' => 'required|string|max:50',
            'notes'         => 'nullable|string',
        ]);
        $data['saisi_par'] = auth()->id();
        $cot = Cotisation::create($data);

        if ($cot->collection_id) {
            $cot->collection->increment('montant_collecte', $cot->montant);
        }

        return redirect()->route('admin.cotisations.index')->with('success', 'Cotisation enregistrée.');
    }

    public function show(string $id) {}

    public function edit(Cotisation $cotisation)
    {
        return inertia('Admin/Cotisations/Form', [
            'cotisation'  => $cotisation->load(['member', 'collection']),
            'membres'     => Member::where('statut', 'actif')->orderBy('nom')->get(['id', 'nom', 'prenom']),
            'collections' => Collection::where('active', true)->orderBy('titre')->get(['id', 'titre']),
        ]);
    }

    public function update(Request $request, Cotisation $cotisation)
    {
        $data = $request->validate([
            'member_id'     => 'required|exists:members,id',
            'collection_id' => 'nullable|exists:collections,id',
            'montant'       => 'required|numeric|min:1',
            'date_paiement' => 'required|date',
            'mode_paiement' => 'required|string|max:50',
            'notes'         => 'nullable|string',
        ]);
        $cotisation->update($data);
        return redirect()->route('admin.cotisations.index')->with('success', 'Cotisation mise à jour.');
    }

    public function destroy(Cotisation $cotisation)
    {
        $cotisation->delete();
        return redirect()->route('admin.cotisations.index')->with('success', 'Cotisation supprimée.');
    }
}
