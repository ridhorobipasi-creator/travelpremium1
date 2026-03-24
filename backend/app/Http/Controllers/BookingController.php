<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Booking::with('user');

        // If not logged in, return 401
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Non-admin/staff only sees their own bookings
        if (!in_array($user->role, ['admin', 'staff'])) {
            $query->where('user_id', $user->id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        return response()->json($query->latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'            => 'required|in:package,car,custom',
            'item_id'         => 'required|integer',
            'start_date'      => 'required|date|after_or_equal:today',
            'end_date'        => 'required|date|after_or_equal:start_date',
            'total_price'     => 'required|numeric|min:0',
            'customer_name'   => 'required|string|max:255',
            'customer_phone'  => 'required|string|min:8|max:20',
            'customer_email'  => 'required|email|max:255',
            'notes'           => 'nullable|string|max:1000',
        ]);

        $booking = Booking::create(array_merge($validated, [
            'user_id' => $request->user()?->id, // Guest support
            'status'  => 'pending',
        ]));

        return response()->json($booking->load('user'), 201);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);
        $booking->update($validated);
        return response()->json($booking->load('user'));
    }

    public function destroy($id)
    {
        Booking::findOrFail($id)->delete();
        return response()->json(['message' => 'Booking berhasil dihapus.']);
    }
}
