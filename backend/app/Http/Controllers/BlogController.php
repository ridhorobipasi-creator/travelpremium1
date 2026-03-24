<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with('author:id,name,photo_url');

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        return response()->json($query->latest()->get());
    }

    public function show($id)
    {
        return response()->json(BlogPost::with('author:id,name,photo_url')->findOrFail($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
        ]);

        $post = BlogPost::create(array_merge($request->all(), [
            'author_id' => $request->user()->id,
        ]));

        return response()->json($post->load('author:id,name,photo_url'), 201);
    }

    public function update(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);
        $validated = $request->validate([
            'title'    => 'sometimes|string|max:255',
        ]);
        $post->update($request->all());
        return response()->json($post->load('author:id,name,photo_url'));
    }

    public function destroy($id)
    {
        BlogPost::findOrFail($id)->delete();
        return response()->json(['message' => 'Artikel berhasil dihapus.']);
    }
}
