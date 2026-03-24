<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Pendapatan (6 Bulan Terakhir)';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        $now = Carbon::now();

        // Ambil data 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            
            // Hitung pendapatan (pesanan sukses)
            $revenue = Order::whereRaw("strftime('%Y', created_at) = ?", [$month->format('Y')])
                ->whereRaw("strftime('%m', created_at) = ?", [$month->format('m')])
                ->where('status', '!=', 'cancelled')
                ->sum('total_price');
                
            $data[] = $revenue;
            
            // Format label (contoh: "Jan 2024")
            $labels[] = $month->translatedFormat('M Y');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Pendapatan (Rp)',
                    'data' => $data,
                    'backgroundColor' => '#10B981', // green
                    'borderColor' => '#059669',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
