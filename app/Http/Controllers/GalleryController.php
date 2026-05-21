<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\GalleryItem;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $albums = Album::publie()
            ->withCount(['items as photos_count' => fn($q) => $q->where('type', 'photo')->where('publie', true)])
            ->withCount(['items as videos_count' => fn($q) => $q->where('type', 'video')->where('publie', true)])
            ->withCount(['items as items_count' => fn($q) => $q->where('publie', true)])
            ->orderBy('created_at', 'desc')
            ->get();

        return inertia('Gallery/Index', ['albums' => $albums]);
    }

    public function show(Request $request, Album $album)
    {
        abort_unless($album->publie, 404);

        $query = $album->items()->where('publie', true)->orderBy('created_at', 'desc');

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $items = $query->paginate(24);

        return inertia('Gallery/Show', [
            'album' => $album,
            'items' => $items,
        ]);
    }
}
