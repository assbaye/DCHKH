<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materiel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterielController extends Controller
{
    public function index()
    {
        $materiels = Materiel::withCount(['empruntsEnCours', 'maintenances'])
            ->orderBy('nom')
            ->paginate(20);

        return inertia('Admin/Materiels/Index', ['materiels' => $materiels]);
    }

    public function create()
    {
        return inertia('Admin/Materiels/Form', [
            'categories' => $this->categories(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'        => 'required|string|max:255',
            'categorie'  => 'required|string|max:100',
            'quantite'   => 'required|integer|min:1',
            'etat'       => 'required|in:bon,usage,hors_service',
            'description'=> 'nullable|string',
            'valeur'     => 'nullable|numeric|min:0',
            'photo'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('materiels', 'public');
        }

        $data['quantite_disponible'] = $data['quantite'];
        $data['created_by'] = auth()->id();

        Materiel::create($data);

        return redirect()->route('admin.materiels.index')->with('success', 'Matériel ajouté.');
    }

    public function show(Materiel $materiel)
    {
        $materiel->load([
            'emprunts.membre',
            'maintenances',
        ]);

        return inertia('Admin/Materiels/Show', ['materiel' => $materiel]);
    }

    public function edit(Materiel $materiel)
    {
        return inertia('Admin/Materiels/Form', [
            'materiel'   => $materiel,
            'categories' => $this->categories(),
        ]);
    }

    public function update(Request $request, Materiel $materiel)
    {
        $data = $request->validate([
            'nom'        => 'required|string|max:255',
            'categorie'  => 'required|string|max:100',
            'quantite'   => 'required|integer|min:1',
            'etat'       => 'required|in:bon,usage,hors_service',
            'description'=> 'nullable|string',
            'valeur'     => 'nullable|numeric|min:0',
            'photo'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($materiel->photo) Storage::disk('public')->delete($materiel->photo);
            $data['photo'] = $request->file('photo')->store('materiels', 'public');
        }

        // Ajuster quantite_disponible si la quantite totale change
        $diff = $data['quantite'] - $materiel->quantite;
        $data['quantite_disponible'] = max(0, $materiel->quantite_disponible + $diff);

        $materiel->update($data);

        return redirect()->route('admin.materiels.index')->with('success', 'Matériel mis à jour.');
    }

    public function destroy(Materiel $materiel)
    {
        if ($materiel->photo) Storage::disk('public')->delete($materiel->photo);
        $materiel->delete();

        return redirect()->route('admin.materiels.index')->with('success', 'Matériel supprimé.');
    }

    private function categories(): array
    {
        return ['Sonorisation', 'Mobilier', 'Textile', 'Véhicule', 'Électroménager', 'Informatique', 'Autre'];
    }
}
