<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('date_event', 'desc')->paginate(15);
        return inertia('Admin/Events/Index', ['events' => $events]);
    }

    public function create()
    {
        return inertia('Admin/Events/Form', [
            'categories' => ['Magal', 'Reunion', 'Collecte', 'Khassaides', 'Autre'],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'categorie'   => 'required|in:Magal,Reunion,Collecte,Khassaides,Autre',
            'date_event'  => 'required|date',
            'heure_event' => 'nullable|date_format:H:i',
            'lieu'        => 'nullable|string|max:255',
            'image'       => 'nullable|image|max:2048',
            'publie'      => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $data['created_by'] = auth()->id();
        Event::create($data);

        return redirect()->route('admin.evenements.index')->with('success', 'Événement créé.');
    }

    public function show(string $id) {}

    public function edit(Event $evenement)
    {
        return inertia('Admin/Events/Form', [
            'event' => $evenement,
            'categories' => ['Magal', 'Reunion', 'Collecte', 'Khassaides', 'Autre'],
        ]);
    }

    public function update(Request $request, Event $evenement)
    {
        $data = $request->validate([
            'titre'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'categorie'   => 'required|in:Magal,Reunion,Collecte,Khassaides,Autre',
            'date_event'  => 'required|date',
            'heure_event' => 'nullable|date_format:H:i',
            'lieu'        => 'nullable|string|max:255',
            'image'       => 'nullable|image|max:2048',
            'publie'      => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($evenement->image) Storage::disk('public')->delete($evenement->image);
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $evenement->update($data);

        return redirect()->route('admin.evenements.index')->with('success', 'Événement mis à jour.');
    }

    public function destroy(Event $evenement)
    {
        if ($evenement->image) Storage::disk('public')->delete($evenement->image);
        $evenement->delete();
        return redirect()->route('admin.evenements.index')->with('success', 'Événement supprimé.');
    }
}
