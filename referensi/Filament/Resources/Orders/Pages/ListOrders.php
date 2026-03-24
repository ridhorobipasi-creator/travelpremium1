<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Buat Pesanan Baru'),
            \Filament\Actions\Action::make('viewLaporan')
                ->label('Laporan')
                ->icon('heroicon-o-chart-bar')
                ->color('info')
                ->url(fn () => route('laporan.pesanan', ['tahun' => now()->year, 'bulan' => now()->month]))
                ->openUrlInNewTab(),
            \Filament\Actions\ActionGroup::make([
                \Filament\Actions\Action::make('downloadCsv')
                    ->label('Export CSV Bulan Ini')
                    ->icon('heroicon-o-document-text')
                    ->url(fn () => route('laporan.pesanan.csv', ['tahun' => now()->year, 'bulan' => now()->month]))
                    ->openUrlInNewTab(),
                \Filament\Actions\Action::make('downloadExcel')
                    ->label('Export Excel Bulan Ini')
                    ->icon('heroicon-o-table-cells')
                    ->url(fn () => route('laporan.pesanan.excel', ['tahun' => now()->year, 'bulan' => now()->month]))
                    ->openUrlInNewTab(),
                \Filament\Actions\Action::make('downloadExcelYear')
                    ->label('Export Excel Tahunan')
                    ->icon('heroicon-o-table-cells')
                    ->url(fn () => route('laporan.pesanan.excel', ['tahun' => now()->year]))
                    ->openUrlInNewTab(),
            ])
            ->label('Export Data')
            ->icon('heroicon-m-arrow-down-tray')
            ->color('gray')
            ->button(),
        ];
    }
}
