<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookingController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $query = Booking::with('user');

        // Priority 1: Multi-Context Isolation
        if ($request->scope) {
            $query->where('scope', $request->scope);
        }

        // Priority 4: Role-Based Filtering
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

    public function show($id)
    {
        $booking = Booking::findOrFail($id);
        
        // Priority 4: Policy Enforcement
        $this->authorize('view', $booking);
        
        return response()->json($booking->load('user'));
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
            'scope'           => 'required|in:tour,outbound',
        ]);

        $booking = Booking::create(array_merge($validated, [
            'user_id' => $request->user()?->id,
            'status'  => 'pending',
        ]));

        return response()->json($booking->load('user'), 201);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        // Priority 4: Policy Enforcement for update
        $this->authorize('update', $booking);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);
        
        $booking->update($validated);
        return response()->json($booking->load('user'));
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        
        // Priority 4: Only Admin can delete (Policy check)
        $this->authorize('delete', $booking);

        $booking->delete();
        return response()->json(['message' => 'Booking berhasil dihapus.']);
    }
}
