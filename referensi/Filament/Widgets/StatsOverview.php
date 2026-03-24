<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\RentalSchedule;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    // protected static ?int $sort = 1;
    // protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $now   = now();
        $year  = $now->year;
        $month = $now->month;
        $yearStr  = $now->format('Y');
        $monthStr = $now->format('m');

        // ── This month ────────────────────────────────────────────────────────
        $monthlyQ = Order::whereRaw("strftime('%Y', created_at) = ?", [$yearStr])
            ->whereRaw("strftime('%m', created_at) = ?", [$monthStr]);

        $totalOrdersBulan = (clone $monthlyQ)->count();
        $revenueBulan     = (clone $monthlyQ)->where('status', '!=', 'cancelled')->sum('total_price');

        // vs last month
        $lastMonth      = $now->copy()->subMonth();
        $lastMonthTotal = Order::whereRaw("strftime('%Y', created_at) = ?", [$lastMonth->format('Y')])
            ->whereRaw("strftime('%m', created_at) = ?", [$lastMonth->format('m')])
            ->count();
        $changeOrders   = $lastMonthTotal > 0
            ? round((($totalOrdersBulan - $lastMonthTotal) / $lastMonthTotal) * 100)
            : 0;

        // ── This year ─────────────────────────────────────────────────────────
        $yearlyQ          = Order::whereRaw("strftime('%Y', created_at) = ?", [$yearStr]);
        $totalOrdersTahun = (clone $yearlyQ)->count();
        $revenueTahun     = (clone $yearlyQ)->where('status', '!=', 'cancelled')->sum('total_price');

        // ── Pending ──────────────────────────────────────────────────────────
        $pendingOrders = Order::where('status', 'pending')->count();

        // ── Active fleet ─────────────────────────────────────────────────────
        $activeFleet = RentalSchedule::where('rental_status', 'booked')
            ->whereDate('start_date', '<=', $now)
            ->whereDate('end_date', '>=', $now)
            ->count();

        // ── Breakdown bulan ini ──────────────────────────────────────────────
        $tourCount   = (clone $monthlyQ)->whereNotNull('product_id')->whereNull('vehicle_id')->whereNull('rental_package_id')->count();
        $rentalCount = (clone $monthlyQ)->whereNotNull('rental_package_id')->count();
        $carCount    = (clone $monthlyQ)->whereNotNull('vehicle_id')->count();

        // Chart data (last 6 months)
        $chartData = [];
        for ($m = max(1, $month - 5); $m <= $month; $m++) {
            $chartData[] = Order::whereRaw("strftime('%Y', created_at) = ?", [$yearStr])
                ->whereRaw("strftime('%m', created_at) = ?", [str_pad($m, 2, '0', STR_PAD_LEFT)])
                ->count();
        }

        return [
            Stat::make('Pesanan Bulan Ini', $totalOrdersBulan)
                ->description(($changeOrders >= 0 ? '↑ +' : '↓ ') . $changeOrders . '% dari bulan lalu')
                ->descriptionIcon($changeOrders >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($changeOrders >= 0 ? 'success' : 'warning')
                ->chart($chartData),

            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($revenueBulan, 0, ',', '.'))
                ->description('Wisata: ' . $tourCount . ' | Rental: ' . $rentalCount . ' | Mobil: ' . $carCount)
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Total Tahun ' . $year, $totalOrdersTahun)
                ->description('Rp ' . number_format($revenueTahun, 0, ',', '.') . ' total pendapatan')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            Stat::make('Pesanan Pending', $pendingOrders)
                ->description('Butuh konfirmasi segera')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingOrders > 5 ? 'danger' : 'warning'),

            Stat::make('Armada Aktif', $activeFleet)
                ->description('Unit sedang digunakan hari ini')
                ->descriptionIcon('heroicon-m-truck')
                ->color('info'),
        ];
    }
}
