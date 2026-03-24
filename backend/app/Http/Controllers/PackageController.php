<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\City;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::with('city');

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhereHas('city', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->city_id) {
            $query->where('city_id', $request->city_id);
        }

        return response()->json($query->latest()->get());
    }

    public function show($identifier)
    {
        $package = Package::with('city.province')
            ->where('id', $identifier)
            ->orWhere('slug', $identifier)
            ->firstOrFail();
        return response()->json($package);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
        ]);

        $package = Package::create($request->all());
        return response()->json($package->load('city'), 201);
    }

    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'sometimes|string|max:255',
        ]);

        $package->update($request->all());
        return response()->json($package->load('city'));
    }

    public function destroy($id)
    {
        Package::findOrFail($id)->delete();
        return response()->json(['message' => 'Paket berhasil dihapus.']);
    }
}
