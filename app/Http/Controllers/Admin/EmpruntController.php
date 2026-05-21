<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Emprunt;
use App\Models\Materiel;
use App\Models\Member;
use Illuminate\Http\Request;

class EmpruntController extends Controller
{
    public function index()
    {
        $emprunts = Emprunt::with(['materiel', 'membre'])
            ->orderByRaw("FIELD(statut,'en_retard','en_cours','rendu')")
            ->orderByDesc('date_emprunt')
            ->paginate(20);

        return inertia('Admin/Emprunts/Index', ['emprunts' => $emprunts]);
    }

    public function create()
    {
        return inertia('Admin/Emprunts/Form', [
            'materiels' => Materiel::where('quantite_disponible', '>', 0)->orderBy('nom')->get(['id', 'nom', 'quantite_disponible']),
            'membres'   => Member::where('statut', 'actif')->orderBy('nom')->get(['id', 'prenom', 'nom']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'materiel_id'       => 'required|exists:materiels,id',
            'member_id'         => 'required|exists:members,id',
            'quantite'          => 'required|integer|min:1',
            'date_emprunt'      => 'required|date',
            'date_retour_prevue'=> 'required|date|after_or_equal:date_emprunt',
            'notes'             => 'nullable|string',
        ]);

        $materiel = Materiel::findOrFail($data['materiel_id']);

        if ($materiel->quantite_disponible < $data['quantite']) {
            return back()->withErrors(['quantite' => 'Quantité disponible insuffisante.']);
        }

        $data['enregistre_par'] = auth()->id();
        $data['statut'] = 'en_cours';

        Emprunt::create($data);

        $materiel->decrement('quantite_disponible', $data['quantite']);

        return redirect()->route('admin.emprunts.index')->with('success', 'Emprunt enregistré.');
    }

    public function show(Emprunt $emprunt)
    {
        $emprunt->load(['materiel', 'membre', 'enregistrePar']);
        return inertia('Admin/Emprunts/Show', ['emprunt' => $emprunt]);
    }

    public function edit(Emprunt $emprunt)
    {
        return inertia('Admin/Emprunts/Form', [
            'emprunt'   => $emprunt,
            'materiels' => Materiel::orderBy('nom')->get(['id', 'nom', 'quantite_disponible']),
            'membres'   => Member::where('statut', 'actif')->orderBy('nom')->get(['id', 'prenom', 'nom']),
        ]);
    }

    public function update(Request $request, Emprunt $emprunt)
    {
        $data = $request->validate([
            'date_retour_prevue' => 'required|date',
            'notes'              => 'nullable|string',
        ]);

        $emprunt->update($data);

        return redirect()->route('admin.emprunts.index')->with('success', 'Emprunt mis à jour.');
    }

    public function retour(Request $request, Emprunt $emprunt)
    {
        if ($emprunt->statut === 'rendu') {
            return back()->withErrors(['statut' => 'Cet emprunt est déjà clôturé.']);
        }

        $emprunt->update([
            'statut'                 => 'rendu',
            'date_retour_effective'  => now()->toDateString(),
        ]);

        $emprunt->materiel->increment('quantite_disponible', $emprunt->quantite);

        return redirect()->route('admin.emprunts.index')->with('success', 'Retour enregistré.');
    }

    public function destroy(Emprunt $emprunt)
    {
        if ($emprunt->statut !== 'rendu') {
            $emprunt->materiel->increment('quantite_disponible', $emprunt->quantite);
        }
        $emprunt->delete();

        return redirect()->route('admin.emprunts.index')->with('success', 'Emprunt supprimé.');
    }
}
