<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RentalPackageResource\Pages;
use App\Models\RentalPackage;
use Filament\Forms;
use Filament\Schemas;
use Filament\Resources\Resource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;

class RentalPackageResource extends Resource
{
    protected static ?string $model = RentalPackage::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Katalog Produk';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Paket Rental';
    
    protected static ?int $navigationSort = 2;

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Informasi Paket')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Paket')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        Forms\Components\TextInput::make('category')
                            ->label('Kategori')
                            ->maxLength(255)
                            ->placeholder('Contoh: Harian, Mingguan, Premium, dll'),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                
                Schemas\Components\Section::make('Harga & Durasi')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('price_per_day')
                            ->label('Harga per Hari')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        Forms\Components\TextInput::make('min_rental_days')
                            ->label('Minimal Sewa (Hari)')
                            ->numeric()
                            ->default(1)
                            ->required(),
                        Forms\Components\TextInput::make('max_rental_days')
                            ->label('Maksimal Sewa (Hari)')
                            ->numeric()
                            ->default(30),
                    ]),
                
                Schemas\Components\Section::make('Fasilitas')
                    ->schema([
                        Forms\Components\TagsInput::make('includes')
                            ->label('Termasuk')
                            ->placeholder('Contoh: Driver, BBM, Parkir'),
                        Forms\Components\TagsInput::make('excludes')
                            ->label('Tidak Termasuk')
                            ->placeholder('Contoh: Makan, Tiket Masuk'),
                    ]),
                
                Schemas\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Banner Utama (Bisa Lebih dari 1)')
                            ->disk('public')
                            ->directory('rental-packages')
                            ->visibility('public')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->required(),
                    ]),
                
                Schemas\Components\Section::make('Status')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                    ]),

                // ─────────────────────────────────────────────────────────────
                // TERJEMAHAN (Bahasa Asing)
                // ─────────────────────────────────────────────────────────────
                Schemas\Components\Section::make('Terjemahan (Bahasa Asing)')
                    ->description('Isi konten dalam bahasa Inggris dan Melayu.')
                    ->collapsed()
                    ->schema([
                        Schemas\Components\Tabs::make('Translations')
                            ->tabs([
                                Schemas\Components\Tabs\Tab::make('English (EN)')
                                    ->schema([
                                        Forms\Components\TextInput::make('translations.en.name')->label('Nama Paket (EN)'),
                                        Forms\Components\TextInput::make('translations.en.category')->label('Kategori (EN)'),
                                        Forms\Components\Textarea::make('translations.en.description')->label('Deskripsi (EN)'),
                                        Forms\Components\TagsInput::make('translations.en.includes')->label('Termasuk (EN)'),
                                        Forms\Components\TagsInput::make('translations.en.excludes')->label('Tidak Termasuk (EN)'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('Malaysia (MS)')
                                    ->schema([
                                        Forms\Components\TextInput::make('translations.ms.name')->label('Nama Paket (MS)'),
                                        Forms\Components\TextInput::make('translations.ms.category')->label('Kategori (MS)'),
                                        Forms\Components\Textarea::make('translations.ms.description')->label('Deskripsi (MS)'),
                                        Forms\Components\TagsInput::make('translations.ms.includes')->label('Termasuk (MS)'),
                                        Forms\Components\TagsInput::make('translations.ms.excludes')->label('Tidak Termasuk (MS)'),
                                    ]),
                            ])->columnSpanFull()
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_per_day')
                    ->label('Harga/Hari')
                    ->money('IDR', true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('min_rental_days')
                    ->label('Min. Hari')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
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
            ->defaultSort('sort_order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRentalPackages::route('/'),
            'create' => Pages\CreateRentalPackage::route('/create'),
            'view' => Pages\ViewRentalPackage::route('/{record}'),
            'edit' => Pages\EditRentalPackage::route('/{record}/edit'),
        ];
    }
}
