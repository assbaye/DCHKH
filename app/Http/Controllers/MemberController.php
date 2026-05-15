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

    public function maCarteShow()
    {
        $member = auth()->user()?->member;
        if (!$member) abort(404);
        return inertia('Members/Card', ['member' => $member]);
    }

    public function carteAdmin(Member $member)
    {
        return inertia('Members/Card', ['member' => $member]);
    }

    public function updatePhoto(\Illuminate\Http\Request $request)
    {
        $member = auth()->user()?->member;
        if (!$member) abort(404);

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($member->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($member->photo);
        }

        $path = $request->file('photo')->store('members', 'public');
        $member->update(['photo' => $path]);

        return back()->with('success', 'Photo mise à jour.');
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(Member $member)
    {
        if ($member->statut !== 'actif') {
            abort(404);
        }
        $member->load('cotisations.collection');
        return inertia('Members/Show', ['member' => $member]);
    }
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
