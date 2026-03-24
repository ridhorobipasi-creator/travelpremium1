<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\Widget;

class OrderSummaryWidget extends Widget
{
    protected static ?int $sort = 2;
    protected string $view = 'filament.widgets.order-summary';
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $now   = now();
        $year  = $now->year;
        $month = $now->month;

        // Monthly orders by type
        $monthly = Order::whereYear('created_at', $year)->whereMonth('created_at', $month)->get();
        $yearly  = Order::whereYear('created_at', $year)->get();

        // Monthly breakdown by month (for table)
        $monthlyBreakdown = [];
        $namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
        for ($m = 1; $m <= 12; $m++) {
            $orders = Order::whereYear('created_at', $year)->whereMonth('created_at', $m);
            $monthlyBreakdown[] = [
                'bulan'    => $namaBulan[$m - 1],
                'total'    => $orders->count(),
                'revenue'  => (clone $orders)->where('status', '!=', 'cancelled')->sum('total_price'),
                'tour'     => (clone $orders)->whereNotNull('product_id')->whereNull('vehicle_id')->whereNull('rental_package_id')->count(),
                'rental'   => (clone $orders)->whereNotNull('rental_package_id')->count(),
                'car'      => (clone $orders)->whereNotNull('vehicle_id')->count(),
                'active'   => $m <= $month,
            ];
        }

        return [
            'year'             => $year,
            'month'            => $month,
            'currentMonthName' => $namaBulan[$month - 1],
            'monthlyBreakdown' => $monthlyBreakdown,
            'yearTotal'        => $yearly->count(),
            'yearRevenue'      => $yearly->where('status', '!=', 'cancelled')->sum('total_price'),
            'monthTotal'       => $monthly->count(),
            'monthRevenue'     => $monthly->where('status', '!=', 'cancelled')->sum('total_price'),
        ];
    }
}
