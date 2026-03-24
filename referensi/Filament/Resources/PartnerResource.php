<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerResource\Pages;
use App\Models\Partner;
use Filament\Forms;
use Filament\Schemas;
use Filament\Resources\Resource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Katalog Produk';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-hand-raised';

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Informasi Partner')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()->maxLength(255),
                        Forms\Components\FileUpload::make('logo')
                            ->image()
                            ->disk('public')
                            ->directory('partners')
                            ->visibility('public')
                            ->required(),
                        Forms\Components\TextInput::make('website')->maxLength(255)->url(),
                        Forms\Components\Textarea::make('description')->rows(3)->columnSpanFull(),
                    ]),
                Schemas\Components\Section::make('Pengaturan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')->numeric(),
                        Forms\Components\Toggle::make('is_active')->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo'),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('website')->url(fn ($record) => $record->website, true)->limit(30),
                Tables\Columns\IconColumn::make('is_active')->boolean()->sortable(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }
}
