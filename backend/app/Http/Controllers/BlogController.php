<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Priority 1: Multi-Context Isolation
     */
    public function index(Request $request)
    {
        $query = BlogPost::with('author:id,name,photo_url');

        if ($request->scope) {
            $query->where('scope', $request->scope);
        }

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        return response()->json($query->latest()->get());
    }

    public function show($identifier)
    {
        $post = BlogPost::with('author:id,name,photo_url')
            ->where('id', $identifier)
            ->orWhere('slug', $identifier)
            ->firstOrFail();
            
        return response()->json($post);
    }

    /**
     * Priority: Image Optimization Setup
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'scope'      => 'required|in:tour,outbound',
            'image_file' => 'nullable|image|max:10240', // 10MB
        ]);

        $data = $request->except('image_file');
        
        if ($request->hasFile('image_file')) {
            $data['image'] = $this->processAndStoreImage($request->file('image_file'), 'blog');
        }

        $post = BlogPost::create(array_merge($data, [
            'author_id' => $request->user()->id,
        ]));

        return response()->json($post->load('author:id,name,photo_url'), 201);
    }

    public function update(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        $request->validate([
          'title'      => 'sometimes|string|max:255',
          'image_file' => 'nullable|image|max:10240',
        ]);

        $data = $request->except('image_file');

        if ($request->hasFile('image_file')) {
            if ($post->image && !Str::startsWith($post->image, 'http')) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $this->processAndStoreImage($request->file('image_file'), 'blog');
        }

        $post->update($data);
        return response()->json($post->load('author:id,name,photo_url'));
    }

    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);
        if ($post->image && !Str::startsWith($post->image, 'http')) {
            Storage::disk('public')->delete($post->image);
        }
        $post->delete();
        return response()->json(['message' => 'Artikel berhasil dihapus.']);
    }

    private function processAndStoreImage($file, $folder)
    {
        $name = time() . '_' . Str::random(10) . '.webp';
        $path = "uploads/{$folder}/{$name}";
        $img = Image::read($file);
        
        if ($img->width() > 1200) {
            $img->scale(width: 1200);
        }
        
        $encoded = $img->toWebp(80);
        Storage::disk('public')->put($path, (string) $encoded);
        
        return $path;
    }
}
