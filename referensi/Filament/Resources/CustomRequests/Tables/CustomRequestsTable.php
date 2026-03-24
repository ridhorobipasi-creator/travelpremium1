<?php

namespace App\Filament\Resources\CustomRequests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CustomRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => str_pad($state, 5, '0', STR_PAD_LEFT)),
                TextColumn::make('customer_name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer_phone')
                    ->label('No. HP')
                    ->searchable(),
                TextColumn::make('num_persons')
                    ->label('Orang')
                    ->sortable(),
                TextColumn::make('trip_duration')
                    ->label('Hari')
                    ->sortable(),
                TextColumn::make('trip_date')
                    ->label('Tanggal Trip')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('budget_range')
                    ->label('Budget')
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new'       => 'info',
                        'reviewed'  => 'warning',
                        'responded' => 'success',
                        'closed'    => 'gray',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'new'       => 'Baru',
                        'reviewed'  => 'Ditinjau',
                        'responded' => 'Ditanggapi',
                        'closed'    => 'Selesai',
                        default     => ucfirst($state),
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Diterima')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'new'       => 'Baru',
                        'reviewed'  => 'Ditinjau',
                        'responded' => 'Ditanggapi',
                        'closed'    => 'Selesai',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                CreateAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                \Filament\Actions\Action::make('viewLaporan')
                    ->label('View Laporan')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn () => route('laporan.pesanan'))
                    ->openUrlInNewTab(),
                \Filament\Actions\Action::make('downloadCsv')
                    ->label('Download CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('secondary')
                    ->url(fn () => route('laporan.pesanan.csv'))
                    ->openUrlInNewTab(),
                \Filament\Actions\Action::make('downloadExcel')
                    ->label('Download Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn () => route('laporan.pesanan.excel'))
                    ->openUrlInNewTab(),
            ]);
    }
}
