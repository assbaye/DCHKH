<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::publie()->orderBy('date_event', 'desc');

        if ($request->categorie) {
            $query->where('categorie', $request->categorie);
        }

        $events = $query->paginate(12);

        return inertia('Events/Index', [
            'events' => $events,
            'categories' => ['Magal', 'Reunion', 'Collecte', 'Khassaides', 'Autre'],
            'filtre' => $request->categorie,
        ]);
    }

    public function show(Event $event)
    {
        $event->load('galerie');
        return inertia('Events/Show', ['event' => $event]);
    }

    public function create() {}
    public function store(Request $request) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
