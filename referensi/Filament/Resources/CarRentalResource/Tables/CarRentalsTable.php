<?php

namespace App\Filament\Resources\CarRentalResource\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CarRentalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Mobil')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('capacity')
                    ->label('Kapasitas')
                    ->suffix(' pax')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('transmission')
                    ->label('Transmisi')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Automatic' ? 'primary' : 'gray'),

                TextColumn::make('fuel_type')
                    ->label('BBM')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Solar' ? 'warning' : 'success'),

                TextColumn::make('year')
                    ->label('Tahun')
                    ->alignCenter(),

                TextColumn::make('price_per_day')
                    ->label('Harga/Hari')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('price_with_driver')
                    ->label('+ Supir')
                    ->money('IDR', locale: 'id')
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_available')
                    ->label('Tersedia')
                    ->boolean()
                    ->alignCenter(),

                IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean()
                    ->alignCenter(),
            ])
            ->filters([
                TernaryFilter::make('is_available')->label('Tersedia'),
                TernaryFilter::make('is_featured')->label('Unggulan'),
                SelectFilter::make('transmission')
                    ->label('Transmisi')
                    ->options(['Manual' => 'Manual', 'Automatic' => 'Matic']),
            ])
            ->defaultSort('sort_order')
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
            ]);
    }
}
