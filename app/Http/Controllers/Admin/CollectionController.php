<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Event;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::withSum('cotisations', 'montant')->orderBy('created_at', 'desc')->paginate(15);
        return inertia('Admin/Collections/Index', ['collections' => $collections]);
    }

    public function create()
    {
        $events = Event::orderBy('date_event', 'desc')->get(['id', 'titre']);
        return inertia('Admin/Collections/Form', ['events' => $events]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'objectif'    => 'required|numeric|min:0',
            'date_debut'  => 'required|date',
            'date_fin'    => 'nullable|date|after:date_debut',
            'active'      => 'boolean',
            'event_id'    => 'nullable|exists:events,id',
        ]);
        $data['created_by'] = auth()->id();
        Collection::create($data);
        return redirect()->route('admin.collections.index')->with('success', 'Collecte créée.');
    }

    public function show(string $id) {}

    public function edit(Collection $collection)
    {
        $events = Event::orderBy('date_event', 'desc')->get(['id', 'titre']);
        return inertia('Admin/Collections/Form', ['collection' => $collection, 'events' => $events]);
    }

    public function update(Request $request, Collection $collection)
    {
        $data = $request->validate([
            'titre'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'objectif'    => 'required|numeric|min:0',
            'date_debut'  => 'required|date',
            'date_fin'    => 'nullable|date|after:date_debut',
            'active'      => 'boolean',
            'event_id'    => 'nullable|exists:events,id',
        ]);
        $collection->update($data);
        return redirect()->route('admin.collections.index')->with('success', 'Collecte mise à jour.');
    }

    public function destroy(Collection $collection)
    {
        $collection->delete();
        return redirect()->route('admin.collections.index')->with('success', 'Collecte supprimée.');
    }
}
