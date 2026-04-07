<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::query();
        
        if ($request->scope) {
            $query->where('scope', $request->scope);
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        return response()->json($query->latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'scope'      => 'required|in:tour,outbound',
            'image_file' => 'required|image|max:10240',
        ]);

        $data = $request->only(['title', 'category', 'scope']);

        if ($request->hasFile('image_file')) {
            $data['image_path'] = $this->processAndStoreImage($request->file('image_file'), 'galleries');
        }

        $gallery = Gallery::create($data);
        return response()->json($gallery, 201);
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        if ($gallery->image_path && !Str::startsWith($gallery->image_path, 'http')) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        $gallery->delete();
        return response()->json(['message' => 'Foto galeri dihapus.']);
    }

    private function processAndStoreImage($file, $folder)
    {
        $name = time() . '_' . Str::random(10) . '.webp';
        $path = "uploads/{$folder}/{$name}";
        $img = Image::read($file);
        
        if ($img->width() > 1920) {
            $img->scale(width: 1920);
        }
        
        $encoded = $img->toWebp(80);
        Storage::disk('public')->put($path, (string) $encoded);
        
        return $path;
    }
}
