<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        return response()->json(City::with('province')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'province_id' => 'nullable|exists:provinces,id',
            'description' => 'nullable|string',
            'image'       => 'nullable|string',
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
        ]);
        $city->update($validated);
        return response()->json($city->load('province'));
    }

    public function destroy($id)
    {
        City::findOrFail($id)->delete();
        return response()->json(['message' => 'Kota berhasil dihapus.']);
    }
}
