<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $items = GalleryItem::with('event')->orderBy('created_at', 'desc')->paginate(24);
        return inertia('Admin/Gallery/Index', ['items' => $items]);
    }

    public function create()
    {
        $events = Event::orderBy('date_event', 'desc')->get(['id', 'titre']);
        return inertia('Admin/Gallery/Form', ['events' => $events]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre'     => 'nullable|string|max:255',
            'description'=> 'nullable|string',
            'type'      => 'required|in:photo,video',
            'fichier'   => 'nullable|image|max:5120',
            'url_video' => 'nullable|url',
            'album'     => 'nullable|string|max:100',
            'event_id'  => 'nullable|exists:events,id',
            'publie'    => 'boolean',
        ]);
        if ($request->hasFile('fichier')) {
            $data['fichier'] = $request->file('fichier')->store('gallery', 'public');
        }
        $data['created_by'] = auth()->id();
        GalleryItem::create($data);
        return redirect()->route('admin.galerie.index')->with('success', 'Élément ajouté à la galerie.');
    }

    public function show(string $id) {}

    public function edit(GalleryItem $galerie)
    {
        $events = Event::orderBy('date_event', 'desc')->get(['id', 'titre']);
        return inertia('Admin/Gallery/Form', ['item' => $galerie, 'events' => $events]);
    }

    public function update(Request $request, GalleryItem $galerie)
    {
        $data = $request->validate([
            'titre'     => 'nullable|string|max:255',
            'description'=> 'nullable|string',
            'type'      => 'required|in:photo,video',
            'fichier'   => 'nullable|image|max:5120',
            'url_video' => 'nullable|url',
            'album'     => 'nullable|string|max:100',
            'event_id'  => 'nullable|exists:events,id',
            'publie'    => 'boolean',
        ]);
        if ($request->hasFile('fichier')) {
            if ($galerie->fichier) Storage::disk('public')->delete($galerie->fichier);
            $data['fichier'] = $request->file('fichier')->store('gallery', 'public');
        }
        $galerie->update($data);
        return redirect()->route('admin.galerie.index')->with('success', 'Élément mis à jour.');
    }

    public function destroy(GalleryItem $galerie)
    {
        if ($galerie->fichier) Storage::disk('public')->delete($galerie->fichier);
        $galerie->delete();
        return redirect()->route('admin.galerie.index')->with('success', 'Élément supprimé.');
    }
}
