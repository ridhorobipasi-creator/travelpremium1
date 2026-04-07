<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Priority 2: Data-Driven Dashboard Stats
     * Returns stats filtered by scope (tour/outbound)
     */
    public function stats(Request $request)
    {
        $scope = $request->query('scope', 'tour');

        $totalRevenue = Booking::where('status', 'confirmed')
            ->where('scope', $scope)
            ->sum('total_price');

        // Note: SQLite strftime for local dev, adjust to MySQL DATE_FORMAT for production if needed
        $revenueByMonth = Booking::where('status', 'confirmed')
            ->where('scope', $scope)
            ->selectRaw("strftime('%Y-%m', created_at) as month, SUM(total_price) as revenue")
            ->groupBy('month')
            ->orderBy('month')
            ->limit(6)
            ->get();

        return response()->json([
            'total_users'    => User::count(),
            'total_packages' => Package::where('scope', $scope)->count(),
            'total_cars'     => $scope === 'tour' ? Car::count() : 0, 
            'total_bookings' => Booking::where('scope', $scope)->count(),
            'total_revenue'  => $totalRevenue,
            'pending_bookings'    => Booking::where('status', 'pending')->where('scope', $scope)->count(),
            'confirmed_bookings'  => Booking::where('status', 'confirmed')->where('scope', $scope)->count(),
            'revenue_by_month'    => $revenueByMonth,
            'recent_bookings'     => Booking::with(['user:id,name,email'])
                ->where('scope', $scope)
                ->latest()->limit(5)->get()
                ->map(function ($booking) {
                    $item = null;
                    if ($booking->type === 'package') {
                        $item = Package::find($booking->item_id);
                    } elseif ($booking->type === 'car') {
                        $item = Car::find($booking->item_id);
                    }

                    $booking->item_details = $item ? [
                        'name'  => $item->name,
                        'image' => $booking->type === 'package' ? ($item->images[0] ?? null) : $item->image,
                    ] : null;

                    return $booking;
                }),
        ]);
    }
}
