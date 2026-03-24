<?php

namespace App\Filament\Resources\PromotionBanners\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class PromotionBannersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('image_url')
                    ->label('Banner'),
                \Filament\Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('position')
                    ->badge()
                    ->sortable(),
                \Filament\Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\TernaryFilter::make('is_active'),
                \Filament\Tables\Filters\SelectFilter::make('position')
                    ->options([
                        'home_top' => 'Home Top',
                        'home_middle' => 'Home Middle',
                        'sidebar' => 'Sidebar',
                    ]),
            ])
            ->recordActions([
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
