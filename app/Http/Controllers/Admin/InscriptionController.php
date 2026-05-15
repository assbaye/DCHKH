<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;

class InscriptionController extends Controller
{
    public function index()
    {
        $en_attente = User::where('is_approved', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return inertia('Admin/Inscriptions/Index', ['en_attente' => $en_attente]);
    }

    public function approuver(User $user)
    {
        $user->update(['is_approved' => true]);

        if (!$user->member) {
            $parts = explode(' ', trim($user->name), 2);
            Member::create([
                'user_id'       => $user->id,
                'prenom'        => $parts[0],
                'nom'           => $parts[1] ?? $parts[0],
                'telephone'     => $user->telephone,
                'date_adhesion' => now()->toDateString(),
                'statut'        => 'actif',
                'role'          => 'membre',
            ]);
        }

        return back()->with('success', "{$user->name} a été approuvé.");
    }

    public function rejeter(User $user)
    {
        $name = $user->name;
        $user->delete();
        return back()->with('success', "L'inscription de {$name} a été rejetée et supprimée.");
    }
}
