<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Priority 1: Multi-Context Isolation
     */
    public function index(Request $request)
    {
        $query = City::with('province');
        
        if ($request->scope) {
            $query->where('scope', $request->scope);
        }

        return response()->json($query->latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'province_id' => 'nullable|exists:provinces,id',
            'description' => 'nullable|string',
            'image'       => 'nullable|string',
            'scope'       => 'required|in:tour,outbound',
        ]);

        $city = City::create($validated);
        return response()->json($city->load('province'), 201);
    }

    public function update(Request $request, $id)
    {
        $city = City::findOrFail($id);
        $validated = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'province_id' => 'nullable|exists:provinces,id',
            'description' => 'nullable|string',
            'image'       => 'nullable|string',
            'scope'       => 'sometimes|in:tour,outbound',
        ]);
        
        $city->update($validated);
        return response()->json($city->load('province'));
    }

    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();
        return response()->json(['message' => 'Kota/Venue berhasil dihapus.']);
    }
}
