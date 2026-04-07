<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    /**
     * Priority 1: Multi-Context Isolation (index)
     */
    public function index(Request $request)
    {
        $query = Package::with('city');

        // Apply Scope-aware filter
        if ($request->scope) {
            $query->where('scope', $request->scope);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhereHas('city', fn($sq) => $sq->where('name', 'like', "%{$request->search}%"));
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return response()->json($query->latest()->get());
    }

    /**
     * Priority 3: Enhanced Slug/ID lookup
     */
    public function show($identifier)
    {
        $package = Package::with('city.province')
            ->where('id', $identifier)
            ->orWhere('slug', $identifier)
            ->firstOrFail();
            
        return response()->json($package);
    }

    /**
     * Priority 2: Image Compression & WebP logic
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'scope' => 'required|in:tour,outbound',
            'image_file' => 'nullable|image|max:10240', // 10MB max
        ]);

        $data = $request->except('image_file');
        
        if ($request->hasFile('image_file')) {
            $data['image'] = $this->processAndStoreImage($request->file('image_file'), 'packages');
            // If the model expects 'images' as array
            $data['images'] = [$data['image']];
        }

        $package = Package::create($data);
        return response()->json($package->load('city'), 201);
    }

    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        $request->validate([
            'name'  => 'sometimes|string|max:255',
            'scope' => 'sometimes|in:tour,outbound',
            'image_file' => 'nullable|image|max:10240',
        ]);

        $data = $request->except('image_file');

        if ($request->hasFile('image_file')) {
            // Delete old image if exists
            if ($package->image && !Str::startsWith($package->image, 'http')) {
                Storage::disk('public')->delete($package->image);
            }
            $data['image'] = $this->processAndStoreImage($request->file('image_file'), 'packages');
            $data['images'] = [$data['image']];
        }

        $package->update($data);
        return response()->json($package->load('city'));
    }

    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        if ($package->image && !Str::startsWith($package->image, 'http')) {
            Storage::disk('public')->delete($package->image);
        }
        $package->delete();
        return response()->json(['message' => 'Paket berhasil dihapus.']);
    }

    /**
     * Utility: Private method for Optimized Image Processing
     */
    private function processAndStoreImage($file, $folder)
    {
        $name = time() . '_' . Str::random(10) . '.webp';
        $path = "uploads/{$folder}/{$name}";
        
        // Use Intervention Image to resize & convert to WebP
        $img = Image::read($file);
        
        // Auto-orient and resize if too wide
        if ($img->width() > 1920) {
            $img->scale(width: 1920);
        }
        
        // Encode as WebP with 80% quality
        $encoded = $img->toWebp(80);
        
        Storage::disk('public')->put($path, (string) $encoded);
        
        return $path;
    }
}
