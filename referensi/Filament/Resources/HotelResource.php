<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HotelResource\Pages;
use App\Models\Hotel;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Tables;
use Filament\Tables\Table;

class HotelResource extends Resource
{
    protected static ?string $model = Hotel::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Katalog Produk';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Hotel';

    protected static ?int $navigationSort = 1;

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Informasi Hotel')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Hotel')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('city')
                            ->label('Kota')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('address')
                            ->label('Alamat')
                            ->maxLength(500)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('rating')
                            ->label('Rating Bintang')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->step(0.1),
                    ]),

                Schemas\Components\Section::make('Deskripsi')
                    ->schema([
                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ]),

                Schemas\Components\Section::make('Media & Status')
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Gambar Utama')
                            ->disk('public')
                            ->directory('hotels')
                            ->visibility('public')
                            ->image()
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Foto')
                    ->circular(false)
                    ->defaultImageUrl(fn () => null),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Hotel')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('Kota')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->sortable()
                    ->suffix(' ★'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
                Tables\Filters\SelectFilter::make('city')
                    ->label('Kota')
                    ->options(fn () => Hotel::pluck('city', 'city')->unique()->sort()->toArray()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\CreateAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('city')
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListHotels::route('/'),
            'create' => Pages\CreateHotel::route('/create'),
            'view'   => Pages\ViewHotel::route('/{record}'),
            'edit'   => Pages\EditHotel::route('/{record}/edit'),
        ];
    }
}
