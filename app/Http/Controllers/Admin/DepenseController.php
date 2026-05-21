<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cotisation;
use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DepenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Depense::query();

        if ($request->filled('mois')) {
            $query->whereYear('date_depense', substr($request->mois, 0, 4))
                  ->whereMonth('date_depense', substr($request->mois, 5, 2));
        }

        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        $depenses = $query->orderByDesc('date_depense')->paginate(20)->withQueryString();

        $total_depenses  = Depense::sum('montant');
        $total_cotisations = Cotisation::sum('montant');
        $solde = $total_cotisations - $total_depenses;

        return inertia('Admin/Depenses/Index', [
            'depenses'          => $depenses,
            'total_depenses'    => $total_depenses,
            'total_cotisations' => $total_cotisations,
            'solde'             => $solde,
            'categories'        => $this->categories(),
            'filters'           => $request->only(['mois', 'categorie']),
        ]);
    }

    public function create()
    {
        return inertia('Admin/Depenses/Form', [
            'categories' => $this->categories(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre'        => 'required|string|max:255',
            'categorie'    => 'required|string|max:100',
            'montant'      => 'required|numeric|min:1',
            'date_depense' => 'required|date',
            'description'  => 'nullable|string',
            'justificatif' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ]);

        if ($request->hasFile('justificatif')) {
            $data['justificatif'] = $request->file('justificatif')->store('depenses', 'public');
        }

        $data['enregistre_par'] = auth()->id();

        Depense::create($data);

        return redirect()->route('admin.depenses.index')->with('success', 'Dépense enregistrée.');
    }

    public function edit(Depense $depense)
    {
        return inertia('Admin/Depenses/Form', [
            'depense'    => $depense,
            'categories' => $this->categories(),
        ]);
    }

    public function update(Request $request, Depense $depense)
    {
        $data = $request->validate([
            'titre'        => 'required|string|max:255',
            'categorie'    => 'required|string|max:100',
            'montant'      => 'required|numeric|min:1',
            'date_depense' => 'required|date',
            'description'  => 'nullable|string',
            'justificatif' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ]);

        if ($request->hasFile('justificatif')) {
            if ($depense->justificatif) Storage::disk('public')->delete($depense->justificatif);
            $data['justificatif'] = $request->file('justificatif')->store('depenses', 'public');
        }

        $depense->update($data);

        return redirect()->route('admin.depenses.index')->with('success', 'Dépense mise à jour.');
    }

    public function destroy(Depense $depense)
    {
        if ($depense->justificatif) Storage::disk('public')->delete($depense->justificatif);
        $depense->delete();

        return redirect()->route('admin.depenses.index')->with('success', 'Dépense supprimée.');
    }

    private function categories(): array
    {
        return ['Événement', 'Matériel', 'Alimentation', 'Transport', 'Entretien', 'Communication', 'Autre'];
    }
}
