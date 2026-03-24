<?php

namespace App\Filament\Resources\TripSchedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class TripSchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('trip_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('order.customer_name')
                    ->label('Nama Pelanggan')
                    ->searchable(),
                \Filament\Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'scheduled' => 'Sudah Booking',
                        'ongoing' => 'Sedang Jalan',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
                \Filament\Tables\Columns\TextColumn::make('order.customer_phone')
                    ->label('Nomor HP'),
                \Filament\Tables\Columns\TextColumn::make('driver_name')
                    ->label('Driver')
                    ->placeholder('Belum ada'),
                \Filament\Tables\Columns\TextColumn::make('service_type')
                    ->label('Layanan')
                    ->badge()
                    ->getStateUsing(fn ($record) => $record->order->vehicle_id ? 'Sewa Mobil' : 'Paket Trip')
                    ->color(fn (string $state): string => match ($state) {
                        'Sewa Mobil' => 'warning',
                        'Paket Trip' => 'info',
                    }),
                \Filament\Tables\Columns\TextColumn::make('vehicle.name')
                    ->label('Jenis Mobil')
                    ->placeholder('-'),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Sudah Booking',
                        'ongoing' => 'Sedang Jalan',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
                \Filament\Tables\Filters\Filter::make('trip_date_month')
                    ->form([
                        \Filament\Forms\Components\Select::make('month')
                            ->label('Bulan')
                            ->options([
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                            ]),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['month'], fn ($query, $month) => $query->whereMonth('trip_date', $month));
                    }),
                \Filament\Tables\Filters\Filter::make('trip_date_year')
                    ->form([
                        \Filament\Forms\Components\Select::make('year')
                            ->label('Tahun')
                            ->options(array_combine(range(now()->year, now()->year - 5), range(now()->year, now()->year - 5)))
                            ->default(now()->year),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['year'], fn ($query, $year) => $query->whereYear('trip_date', $year));
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
