<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::where('is_approved', true);
        
        if ($request->package_id) {
            $query->where('package_id', $request->package_id);
        }

        if ($request->scope) {
            $query->where('scope', $request->scope);
        }

        return response()->json($query->latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id'    => 'required|exists:packages,id',
            'customer_name' => 'required|string|max:255',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'required|string',
            'scope'         => 'required|in:tour,outbound'
        ]);

        // Auto-approve if user is logged in staff/admin, else pending
        $isApproved = $request->user() && in_array($request->user()->role, ['admin', 'staff']);

        $review = Review::create(array_merge($validated, [
            'user_id'     => $request->user()?->id,
            'is_approved' => $isApproved
        ]));

        return response()->json($review, 201);
    }

    /** Administration methods */
    public function adminIndex(Request $request)
    {
        $query = Review::with('package');
        if ($request->scope) $query->where('scope', $request->scope);
        return response()->json($query->latest()->get());
    }

    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => true]);
        return response()->json(['message' => 'Review disetujui']);
    }

    public function destroy($id)
    {
        Review::findOrFail($id)->delete();
        return response()->json(['message' => 'Review dihapus']);
    }
}
