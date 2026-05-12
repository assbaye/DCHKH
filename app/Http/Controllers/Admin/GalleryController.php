<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

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
        $request->validate([
            'type'      => 'required|in:photo,video',
            'fichiers'  => 'nullable|array',
            'fichiers.*'=> 'image|max:10240',
            'url_video' => 'nullable|url',
            'titre'     => 'nullable|string|max:255',
            'description'=> 'nullable|string',
            'album'     => 'nullable|string|max:100',
            'event_id'  => 'nullable|exists:events,id',
            'publie'    => 'boolean',
        ]);

        $base = [
            'type'        => $request->type,
            'titre'       => $request->titre,
            'description' => $request->description,
            'album'       => $request->album,
            'event_id'    => $request->event_id ?: null,
            'publie'      => $request->boolean('publie', true),
            'created_by'  => auth()->id(),
        ];

        if ($request->type === 'video') {
            GalleryItem::create(array_merge($base, ['url_video' => $request->url_video]));
        } else {
            foreach ($request->file('fichiers', []) as $fichier) {
                $path = 'gallery/' . uniqid() . '.jpg';
                $image = Image::read($fichier)
                    ->scaleDown(width: 1920, height: 1080)
                    ->toJpeg(quality: 82);
                Storage::disk('public')->put($path, $image);
                GalleryItem::create(array_merge($base, ['fichier' => $path]));
            }
        }

        return redirect()->route('admin.galerie.index')->with('success', 'Média(s) ajouté(s) à la galerie.');
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
        if ($request->hasFile('fichiers')) {
            if ($galerie->fichier) Storage::disk('public')->delete($galerie->fichier);
            $path = 'gallery/' . uniqid() . '.jpg';
            $image = Image::read($request->file('fichiers')[0])
                ->scaleDown(width: 1920, height: 1080)
                ->toJpeg(quality: 82);
            Storage::disk('public')->put($path, $image);
            $data['fichier'] = $path;
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
