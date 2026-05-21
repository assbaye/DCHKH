<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Reunion;
use Illuminate\Http\Request;

class ReunionController extends Controller
{
    public function index()
    {
        $reunions = Reunion::withCount('membres')
            ->orderBy('date_reunion', 'desc')
            ->paginate(20);

        return inertia('Admin/Reunions/Index', ['reunions' => $reunions]);
    }

    public function create()
    {
        $membres = Member::orderBy('nom')->get(['id', 'nom', 'prenom']);
        return inertia('Admin/Reunions/Form', ['membres' => $membres]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre'         => 'required|string|max:255',
            'date_reunion'  => 'required|date',
            'heure_debut'   => 'nullable|date_format:H:i',
            'heure_fin'     => 'nullable|date_format:H:i',
            'lieu'          => 'nullable|string|max:255',
            'ordre_du_jour' => 'nullable|string',
            'compte_rendu'  => 'nullable|string',
            'statut'        => 'required|in:planifiee,terminee',
            'participant_ids' => 'nullable|array',
            'participant_ids.*' => 'exists:members,id',
        ]);

        $reunion = Reunion::create(array_merge($data, ['created_by' => auth()->id()]));

        if (!empty($data['participant_ids'])) {
            $reunion->membres()->sync($data['participant_ids']);
        }

        return redirect()->route('admin.reunions.index')->with('success', 'Réunion créée avec succès.');
    }

    public function show(Reunion $reunion)
    {
        $reunion->load('membres', 'createur');
        return inertia('Admin/Reunions/Show', ['reunion' => $reunion]);
    }

    public function edit(Reunion $reunion)
    {
        $reunion->load('membres');
        $membres = Member::orderBy('nom')->get(['id', 'nom', 'prenom']);
        $participantIds = $reunion->membres->pluck('id')->toArray();

        return inertia('Admin/Reunions/Form', [
            'reunion'        => $reunion,
            'membres'        => $membres,
            'participantIds' => $participantIds,
        ]);
    }

    public function update(Request $request, Reunion $reunion)
    {
        $data = $request->validate([
            'titre'         => 'required|string|max:255',
            'date_reunion'  => 'required|date',
            'heure_debut'   => 'nullable|date_format:H:i',
            'heure_fin'     => 'nullable|date_format:H:i',
            'lieu'          => 'nullable|string|max:255',
            'ordre_du_jour' => 'nullable|string',
            'compte_rendu'  => 'nullable|string',
            'statut'        => 'required|in:planifiee,terminee',
            'participant_ids' => 'nullable|array',
            'participant_ids.*' => 'exists:members,id',
        ]);

        $reunion->update($data);
        $reunion->membres()->sync($data['participant_ids'] ?? []);

        return redirect()->route('admin.reunions.index')->with('success', 'Réunion mise à jour.');
    }

    public function destroy(Reunion $reunion)
    {
        $reunion->delete();
        return redirect()->route('admin.reunions.index')->with('success', 'Réunion supprimée.');
    }
}
