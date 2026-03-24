<?php

namespace App\Filament\Resources\TripData\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TripDataTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('bulan')
                    ->label('Bulan')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('nama_pelanggan')
                    ->label('Pelanggan')
                    ->searchable()
                    ->default('-'),

                TextColumn::make('nomor_hp')
                    ->label('No. HP')
                    ->default('-'),

                TextColumn::make('layanan')
                    ->label('Layanan')
                    ->default('-'),

                TextColumn::make('nama_driver')
                    ->label('Driver')
                    ->default('-'),

                TextColumn::make('jenis_mobil')
                    ->label('Kendaraan')
                    ->description(fn ($record) => $record->plat_mobil)
                    ->default('-'),

                TextColumn::make('jumlah_hari')
                    ->label('Hari')
                    ->alignCenter()
                    ->default('-'),

                IconColumn::make('drone')
                    ->label('Drone')
                    ->boolean()
                    ->alignCenter(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($record) => $record->status_label)
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'primary',
                        'ongoing'   => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default     => 'gray',
                    }),

                TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR', locale: 'id')
                    ->default('-'),

                TextColumn::make('deposit')
                    ->label('Deposit')
                    ->money('IDR', locale: 'id')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('pelunasan')
                    ->label('Pelunasan')
                    ->money('IDR', locale: 'id')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('bulan')
                    ->label('Bulan')
                    ->options([
                        'Januari'   => 'Januari',
                        'Februari'  => 'Februari',
                        'Maret'     => 'Maret',
                        'April'     => 'April',
                        'Mei'       => 'Mei',
                        'Juni'      => 'Juni',
                        'Juli'      => 'Juli',
                        'Agustus'   => 'Agustus',
                        'September' => 'September',
                        'Oktober'   => 'Oktober',
                        'November'  => 'November',
                        'Desember'  => 'Desember',
                    ]),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'   => 'Belum Konfirmasi',
                        'confirmed' => 'Terkonfirmasi',
                        'ongoing'   => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
            ])
            ->defaultSort('tanggal', 'asc')
            ->recordAction('edit')
            ->recordUrl(null)
            ->actions([
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
