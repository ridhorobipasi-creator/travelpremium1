<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use App\Models\Package;
use App\Models\User;
use App\Models\BlogPost;

class AdminController extends Controller
{
    public function stats()
    {
        $totalRevenue = Booking::where('status', 'confirmed')->sum('total_price');
        $revenueByMonth = Booking::where('status', 'confirmed')
            ->selectRaw("strftime('%Y-%m', created_at) as month, SUM(total_price) as revenue")
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        return response()->json([
            'total_users'    => User::count(),
            'total_packages' => Package::count(),
            'total_cars'     => Car::count(),
            'total_bookings' => Booking::count(),
            'total_revenue'  => $totalRevenue,
            'pending_bookings'    => Booking::where('status', 'pending')->count(),
            'confirmed_bookings'  => Booking::where('status', 'confirmed')->count(),
            'cancelled_bookings'  => Booking::where('status', 'cancelled')->count(),
            'revenue_by_month'    => $revenueByMonth,
            'recent_bookings'     => Booking::with(['user:id,name,email'])
                ->latest()->limit(5)->get()
                ->map(function ($booking) {
                    // Manually load item details based on type
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
