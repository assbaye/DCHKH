<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with('user')->orderBy('nom');
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nom', 'like', "%{$request->search}%")
                  ->orWhere('prenom', 'like', "%{$request->search}%")
                  ->orWhere('telephone', 'like', "%{$request->search}%");
            });
        }
        if ($request->statut) $query->where('statut', $request->statut);
        $membres = $query->paginate(20);
        return inertia('Admin/Members/Index', ['membres' => $membres, 'filters' => $request->only('search', 'statut')]);
    }

    public function create()
    {
        return inertia('Admin/Members/Form', [
            'roles'             => ['membre', 'secretaire', 'tresorier', 'gestionnaire', 'admin'],
            'matricule_propose' => Member::genererMatricule(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'           => 'required|string|max:100',
            'prenom'        => 'required|string|max:100',
            'telephone'     => 'nullable|string|max:20',
            'adresse'       => 'nullable|string|max:255',
            'ville'         => 'nullable|string|max:100',
            'date_adhesion' => 'required|date',
            'statut'        => 'required|in:actif,inactif',
            'role'          => 'required|in:membre,secretaire,tresorier,gestionnaire,admin',
            'matricule'     => 'nullable|string|max:20|unique:members,matricule',
        ]);

        if (empty($data['matricule'])) {
            $data['matricule'] = Member::genererMatricule();
        }

        Member::create($data);
        return redirect()->route('admin.membres.index')->with('success', 'Membre ajouté avec succès.');
    }

    public function show(string $id) {}

    public function edit(Member $membre)
    {
        return inertia('Admin/Members/Form', [
            'membre' => $membre,
            'roles'  => ['membre', 'secretaire', 'tresorier', 'gestionnaire', 'admin'],
        ]);
    }

    public function update(Request $request, Member $membre)
    {
        $data = $request->validate([
            'nom'           => 'required|string|max:100',
            'prenom'        => 'required|string|max:100',
            'telephone'     => 'nullable|string|max:20',
            'adresse'       => 'nullable|string|max:255',
            'ville'         => 'nullable|string|max:100',
            'date_adhesion' => 'required|date',
            'statut'        => 'required|in:actif,inactif',
            'role'          => 'required|in:membre,secretaire,tresorier,gestionnaire,admin',
            'matricule'     => 'nullable|string|max:20|unique:members,matricule,' . $membre->id,
        ]);
        $membre->update($data);
        return redirect()->route('admin.membres.index')->with('success', 'Membre mis à jour.');
    }

    public function destroy(Member $membre)
    {
        $membre->delete();
        return redirect()->route('admin.membres.index')->with('success', 'Membre supprimé.');
    }
}
