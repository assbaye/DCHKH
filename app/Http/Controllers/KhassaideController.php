<?php

namespace App\Http\Controllers;

use App\Models\Khassaide;
use Illuminate\Http\Request;

class KhassaideController extends Controller
{
    public function index(Request $request)
    {
        $query = Khassaide::publie()->orderBy('created_at', 'desc');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('titre', 'like', "%{$request->search}%")
                  ->orWhere('auteur', 'like', "%{$request->search}%")
                  ->orWhere('interprete', 'like', "%{$request->search}%");
            });
        }

        $khassaides = $query->paginate(20);

        return inertia('Khassaides/Index', [
            'khassaides' => $khassaides,
            'search' => $request->search,
        ]);
    }

    public function show(Khassaide $khassaide)
    {
        return inertia('Khassaides/Show', ['khassaide' => $khassaide]);
    }

    public function create() {}
    public function store(Request $request) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
