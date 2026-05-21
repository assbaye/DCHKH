<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Materiel;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = Maintenance::with('materiel')
            ->orderByRaw("FIELD(statut,'en_cours','planifiee','terminee')")
            ->orderByDesc('date_maintenance')
            ->paginate(20);

        return inertia('Admin/Maintenances/Index', ['maintenances' => $maintenances]);
    }

    public function create()
    {
        return inertia('Admin/Maintenances/Form', [
            'materiels' => Materiel::orderBy('nom')->get(['id', 'nom']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'materiel_id'      => 'required|exists:materiels,id',
            'titre'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'cout'             => 'nullable|numeric|min:0',
            'date_maintenance' => 'required|date',
            'statut'           => 'required|in:planifiee,en_cours,terminee',
        ]);

        $data['enregistre_par'] = auth()->id();

        Maintenance::create($data);

        return redirect()->route('admin.maintenances.index')->with('success', 'Maintenance enregistrée.');
    }

    public function edit(Maintenance $maintenance)
    {
        return inertia('Admin/Maintenances/Form', [
            'maintenance' => $maintenance,
            'materiels'   => Materiel::orderBy('nom')->get(['id', 'nom']),
        ]);
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $data = $request->validate([
            'materiel_id'      => 'required|exists:materiels,id',
            'titre'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'cout'             => 'nullable|numeric|min:0',
            'date_maintenance' => 'required|date',
            'statut'           => 'required|in:planifiee,en_cours,terminee',
        ]);

        $maintenance->update($data);

        return redirect()->route('admin.maintenances.index')->with('success', 'Maintenance mise à jour.');
    }

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('admin.maintenances.index')->with('success', 'Maintenance supprimée.');
    }
}
