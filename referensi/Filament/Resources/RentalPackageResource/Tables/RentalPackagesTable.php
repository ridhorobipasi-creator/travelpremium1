<?php

namespace App\Filament\Resources\RentalPackageResource\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class RentalPackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('price_per_day')
                    ->label('Harga/Hari')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('min_rental_days')
                    ->label('Min. Hari')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('max_rental_days')
                    ->label('Maks. Hari')
                    ->alignCenter()
                    ->default('—'),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->alignCenter(),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Aktif'),
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
