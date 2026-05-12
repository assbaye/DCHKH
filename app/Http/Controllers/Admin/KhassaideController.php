<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Khassaide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KhassaideController extends Controller
{
    public function index()
    {
        $khassaides = Khassaide::orderBy('created_at', 'desc')->paginate(20);
        return inertia('Admin/Khassaides/Index', ['khassaides' => $khassaides]);
    }

    public function create()
    {
        return inertia('Admin/Khassaides/Form', ['langues' => ['arabe', 'wolof', 'français', 'autre']]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre'         => 'required|string|max:255',
            'auteur'        => 'required|string|max:255',
            'description'   => 'nullable|string',
            'paroles'       => 'nullable|string',
            'langue'        => 'required|in:arabe,wolof,français,autre',
            'fichier_audio' => 'nullable|file|mimes:mp3,wav,ogg|max:20480',
            'telechargeable'=> 'boolean',
            'publie'        => 'boolean',
        ]);
        if ($request->hasFile('fichier_audio')) {
            $data['fichier_audio'] = $request->file('fichier_audio')->store('khassaides', 'public');
        }
        $data['created_by'] = auth()->id();
        Khassaide::create($data);
        return redirect()->route('admin.khassaides.index')->with('success', 'Khassaïde ajouté.');
    }

    public function show(string $id) {}

    public function edit(Khassaide $khassaide)
    {
        return inertia('Admin/Khassaides/Form', ['khassaide' => $khassaide, 'langues' => ['arabe', 'wolof', 'français', 'autre']]);
    }

    public function update(Request $request, Khassaide $khassaide)
    {
        $data = $request->validate([
            'titre'         => 'required|string|max:255',
            'auteur'        => 'required|string|max:255',
            'description'   => 'nullable|string',
            'paroles'       => 'nullable|string',
            'langue'        => 'required|in:arabe,wolof,français,autre',
            'fichier_audio' => 'nullable|file|mimes:mp3,wav,ogg|max:20480',
            'telechargeable'=> 'boolean',
            'publie'        => 'boolean',
        ]);
        if ($request->hasFile('fichier_audio')) {
            if ($khassaide->fichier_audio) Storage::disk('public')->delete($khassaide->fichier_audio);
            $data['fichier_audio'] = $request->file('fichier_audio')->store('khassaides', 'public');
        }
        $khassaide->update($data);
        return redirect()->route('admin.khassaides.index')->with('success', 'Khassaïde mis à jour.');
    }

    public function destroy(Khassaide $khassaide)
    {
        if ($khassaide->fichier_audio) Storage::disk('public')->delete($khassaide->fichier_audio);
        $khassaide->delete();
        return redirect()->route('admin.khassaides.index')->with('success', 'Khassaïde supprimé.');
    }
}
