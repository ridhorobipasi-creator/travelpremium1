<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarRentalResource\Pages;
use App\Models\CarRental;
use Filament\Forms;
use Filament\Schemas;
use Filament\Resources\Resource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;

class CarRentalResource extends Resource
{
    protected static ?string $model = CarRental::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Aset & Armada';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Rental Mobil';
    
    protected static ?int $navigationSort = 3;

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Informasi Kendaraan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('vehicle_id')
                            ->label('Pilih Kendaraan')
                            ->relationship('vehicle', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state) {
                                    $vehicle = \App\Models\Vehicle::find($state);
                                    if ($vehicle) {
                                        $set('name', $vehicle->name);
                                        $set('capacity', $vehicle->capacity);
                                    }
                                }
                            }),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Mobil')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        Forms\Components\TextInput::make('category')
                            ->label('Kategori')
                            ->maxLength(255)
                            ->placeholder('Contoh: SUV, MPV, Sedan, dll'),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('capacity')
                            ->label('Kapasitas Penumpang')
                            ->numeric()
                            ->required(),
                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ]),
                
                Schemas\Components\Section::make('Harga Rental')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('price_per_day')
                            ->label('Harga per Hari')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        Forms\Components\TextInput::make('price_with_driver')
                            ->label('Harga + Driver')
                            ->numeric()
                            ->prefix('Rp'),
                        
                        Forms\Components\Repeater::make('pricing_details')
                            ->label('Pengaturan Harga Custom (Berdasarkan Jumlah Hari)')
                            ->schema([
                                Forms\Components\TextInput::make('days')
                                    ->label('Jumlah Hari')
                                    ->numeric()
                                    ->required()
                                    ->placeholder('Misal: 1'),
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga/Hari')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->placeholder('Misal: 250000'),
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->createItemButtonLabel('Tambah Harga Custom')
                            ->helperText('Gunakan ini jika Anda ingin memberikan harga berbeda untuk durasi rental tertentu (misal: sewa 3 hari harga lebih murah).'),
                    ]),
                
                Schemas\Components\Section::make('Spesifikasi')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('transmission')
                            ->label('Transmisi')
                            ->placeholder('Manual / Automatic'),
                        Forms\Components\TextInput::make('fuel_type')
                            ->label('Jenis BBM')
                            ->placeholder('Bensin / Diesel'),
                        Forms\Components\TextInput::make('year')
                            ->label('Tahun')
                            ->numeric(),
                    ]),
                
                Schemas\Components\Section::make('Fasilitas & Ketentuan')
                    ->schema([
                        Forms\Components\TagsInput::make('features')
                            ->label('Fitur Kendaraan')
                            ->placeholder('Contoh: AC, Audio, USB Port'),
                        Forms\Components\TagsInput::make('includes')
                            ->label('Termasuk')
                            ->placeholder('Contoh: Asuransi, BBM'),
                        Forms\Components\Textarea::make('terms')
                            ->label('Syarat & Ketentuan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                
                Schemas\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Banner Utama (Bisa Lebih dari 1)')
                            ->disk('public')
                            ->directory('car-rentals')
                            ->visibility('public')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->acceptedFileTypes(['image/jpeg','image/png','image/webp'])
                            ->required()
                            ->helperText('Akan tampil sebagai slider di halaman detail'),
                        Forms\Components\FileUpload::make('gallery_images')
                            ->label('Galeri Pendukung')
                            ->disk('public')
                            ->directory('car-rentals/gallery')
                            ->visibility('public')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg','image/png','image/webp'])
                            ->multiple()
                            ->reorderable()
                            ->columnSpanFull(),
                    ]),
                
                Schemas\Components\Section::make('Status & SEO')
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Toggle::make('is_available')
                            ->label('Tersedia')
                            ->default(true),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Unggulan')
                            ->default(false),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(2)
                            ->columnSpanFull(),
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
                                        Forms\Components\TextInput::make('translations.en.name')->label('Nama Mobil (EN)'),
                                        Forms\Components\TextInput::make('translations.en.category')->label('Kategori (EN)'),
                                        Forms\Components\RichEditor::make('translations.en.description')->label('Deskripsi (EN)'),
                                        Forms\Components\TagsInput::make('translations.en.features')->label('Fitur Kendaraan (EN)'),
                                        Forms\Components\TagsInput::make('translations.en.includes')->label('Termasuk (EN)'),
                                        Forms\Components\Textarea::make('translations.en.terms')->label('Syarat & Ketentuan (EN)'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('Malaysia (MS)')
                                    ->schema([
                                        Forms\Components\TextInput::make('translations.ms.name')->label('Nama Mobil (MS)'),
                                        Forms\Components\TextInput::make('translations.ms.category')->label('Kategori (MS)'),
                                        Forms\Components\RichEditor::make('translations.ms.description')->label('Deskripsi (MS)'),
                                        Forms\Components\TagsInput::make('translations.ms.features')->label('Fitur Kendaraan (MS)'),
                                        Forms\Components\TagsInput::make('translations.ms.includes')->label('Termasuk (MS)'),
                                        Forms\Components\Textarea::make('translations.ms.terms')->label('Syarat & Ketentuan (MS)'),
                                    ]),
                            ])->columnSpanFull()
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Foto'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Mobil')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vehicle.name')
                    ->label('Armada')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->label('Kapasitas')
                    ->suffix(' orang')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_per_day')
                    ->label('Harga/Hari')
                    ->money('IDR', true)
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_available')
                    ->label('Tersedia')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_available'),
                Tables\Filters\TernaryFilter::make('is_featured'),
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
            'index' => Pages\ListCarRentals::route('/'),
            'create' => Pages\CreateCarRental::route('/create'),
            'view' => Pages\ViewCarRental::route('/{record}'),
            'edit' => Pages\EditCarRental::route('/{record}/edit'),
        ];
    }
}
