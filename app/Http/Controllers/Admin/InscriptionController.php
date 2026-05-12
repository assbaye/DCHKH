<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        return back()->with('success', "{$user->name} a été approuvé.");
    }

    public function rejeter(User $user)
    {
        $name = $user->name;
        $user->delete();
        return back()->with('success', "L'inscription de {$name} a été rejetée et supprimée.");
    }
}
