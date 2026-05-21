<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class AlbumController extends Controller
{
    public function index()
    {
        $albums = Album::withCount('items')->orderBy('created_at', 'desc')->paginate(20);
        return inertia('Admin/Albums/Index', ['albums' => $albums]);
    }

    public function create()
    {
        return inertia('Admin/Albums/Form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'         => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:5120',
            'publie'      => 'boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            $path = 'albums/' . uniqid() . '.jpg';
            $image = Image::read($request->file('cover_image'))
                ->scaleDown(width: 1920, height: 1080)
                ->toJpeg(quality: 82);
            Storage::disk('public')->put($path, $image);
            $data['cover_image'] = $path;
        }

        Album::create(array_merge($data, [
            'publie'     => $request->boolean('publie', true),
            'created_by' => auth()->id(),
        ]));

        return redirect()->route('admin.albums.index')->with('success', 'Album créé avec succès.');
    }

    public function show(Album $album) {}

    public function edit(Album $album)
    {
        return inertia('Admin/Albums/Form', ['album' => $album]);
    }

    public function update(Request $request, Album $album)
    {
        $data = $request->validate([
            'nom'         => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:5120',
            'publie'      => 'boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($album->cover_image) Storage::disk('public')->delete($album->cover_image);
            $path = 'albums/' . uniqid() . '.jpg';
            $image = Image::read($request->file('cover_image'))
                ->scaleDown(width: 1920, height: 1080)
                ->toJpeg(quality: 82);
            Storage::disk('public')->put($path, $image);
            $data['cover_image'] = $path;
        }

        $album->update(array_merge($data, ['publie' => $request->boolean('publie')]));
        return redirect()->route('admin.albums.index')->with('success', 'Album mis à jour.');
    }

    public function destroy(Album $album)
    {
        if ($album->cover_image) Storage::disk('public')->delete($album->cover_image);
        $album->delete();
        return redirect()->route('admin.albums.index')->with('success', 'Album supprimé.');
    }
}
