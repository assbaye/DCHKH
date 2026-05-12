<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::where('statut', 'actif')->orderBy('nom');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nom', 'like', "%{$request->search}%")
                  ->orWhere('prenom', 'like', "%{$request->search}%")
                  ->orWhere('ville', 'like', "%{$request->search}%");
            });
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        $membres = $query->paginate(24)->withQueryString();

        $stats = [
            'total'       => Member::where('statut', 'actif')->count(),
            'admins'      => Member::where('role', 'admin')->count(),
            'moderateurs' => Member::where('role', 'moderateur')->count(),
        ];

        return inertia('Members/Index', [
            'membres'       => $membres,
            'stats'         => $stats,
            'search'        => $request->search,
            'filtre_role'   => $request->role,
        ]);
    }

    public function profil()
    {
        $member = auth()->user()?->member()->with('cotisations.collection')->first();
        return inertia('Members/Profil', ['member' => $member]);
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
