<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::query();

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('type', 'like', "%{$request->search}%");
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return response()->json($query->latest()->get());
    }

    public function show($id)
    {
        return response()->json(Car::findOrFail($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
        ]);

        $car = Car::create($request->all());
        return response()->json($car, 201);
    }

    public function update(Request $request, $id)
    {
        $car = Car::findOrFail($id);

        $validated = $request->validate([
            'name'          => 'sometimes|string|max:255',
        ]);

        $car->update($request->all());
        return response()->json($car);
    }

    public function destroy($id)
    {
        Car::findOrFail($id)->delete();
        return response()->json(['message' => 'Mobil berhasil dihapus.']);
    }
}
