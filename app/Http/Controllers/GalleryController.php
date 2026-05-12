<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = GalleryItem::publie()->with('event')->orderBy('created_at', 'desc');

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->album) {
            $query->where('album', $request->album);
        }

        $items = $query->paginate(24);
        $albums = GalleryItem::publie()->whereNotNull('album')->distinct()->pluck('album');

        return inertia('Gallery/Index', [
            'items' => $items,
            'albums' => $albums,
            'filtre_type' => $request->type,
            'filtre_album' => $request->album,
        ]);
    }

    public function show(string $id) {}
    public function create() {}
    public function store(Request $request) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
