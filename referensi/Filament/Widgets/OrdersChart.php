<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class OrdersChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Jumlah Pesanan (6 Bulan Terakhir)';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        $now = Carbon::now();

        // Ambil data 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            
            // Hitung jumlah pesanan
            $count = Order::whereRaw("strftime('%Y', created_at) = ?", [$month->format('Y')])
                ->whereRaw("strftime('%m', created_at) = ?", [$month->format('m')])
                ->count();
                
            $data[] = $count;
            $labels[] = $month->translatedFormat('M Y');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Pesanan',
                    'data' => $data,
                    'backgroundColor' => '#3B82F6', // blue
                    'borderColor' => '#2563EB',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
