<?php

namespace App\Filament\Resources\CarRentalResource\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CarRentalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informasi Mobil')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Nama Mobil')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    Select::make('transmission')
                        ->label('Transmisi')
                        ->options(['Manual' => 'Manual', 'Automatic' => 'Matic'])
                        ->nullable(),

                    Select::make('fuel_type')
                        ->label('Bahan Bakar')
                        ->options(['Bensin' => 'Bensin', 'Solar' => 'Solar', 'Hybrid' => 'Hybrid'])
                        ->nullable(),

                    TextInput::make('capacity')
                        ->label('Kapasitas (penumpang)')
                        ->numeric()
                        ->required(),

                    TextInput::make('year')
                        ->label('Tahun Kendaraan')
                        ->numeric()
                        ->nullable(),
                ]),

            Section::make('Harga')
                ->columns(3)
                ->schema([
                    TextInput::make('price_per_day')
                        ->label('Harga Per Hari')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),

                    TextInput::make('price_per_12_hours')
                        ->label('Harga Per 12 Jam')
                        ->numeric()
                        ->prefix('Rp')
                        ->nullable(),

                    TextInput::make('price_with_driver')
                        ->label('Harga + Supir')
                        ->numeric()
                        ->prefix('Rp')
                        ->nullable(),
                ]),

            Section::make('Deskripsi & Ketentuan')
                ->schema([
                    RichEditor::make('description')
                        ->label('Deskripsi')
                        ->columnSpanFull(),

                    Textarea::make('terms')
                        ->label('Syarat & Ketentuan')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),

            Section::make('Fitur & Include/Exclude')
                ->columns(2)
                ->schema([
                    TagsInput::make('features')
                        ->label('Fitur Kendaraan')
                        ->placeholder('Tambah fitur...')
                        ->nullable(),

                    TagsInput::make('includes')
                        ->label('Sudah Termasuk')
                        ->placeholder('Tambah item...')
                        ->nullable(),
                ]),

            Section::make('Gambar')
                ->columns(2)
                ->schema([
                    FileUpload::make('featured_image')
                        ->label('Foto Utama')
                        ->image()
                        ->disk('public')
                        ->directory('car-rentals')
                        ->visibility('public'),

                    FileUpload::make('gallery_images')
                        ->label('Galeri Foto')
                        ->image()
                        ->disk('public')
                        ->directory('car-rentals/gallery')
                        ->visibility('public')
                        ->multiple(),
                ]),

            Section::make('Pengaturan')
                ->columns(3)
                ->schema([
                    Toggle::make('is_available')
                        ->label('Tersedia')
                        ->default(true),

                    Toggle::make('is_featured')
                        ->label('Tampilkan di Beranda')
                        ->default(false),

                    TextInput::make('sort_order')
                        ->label('Urutan Tampil')
                        ->numeric()
                        ->default(0),
                ]),

            Section::make('SEO')
                ->collapsed()
                ->schema([
                    TextInput::make('meta_title')
                        ->label('Meta Title')
                        ->nullable(),
                    Textarea::make('meta_description')
                        ->label('Meta Description')
                        ->rows(2)
                        ->nullable(),
                ]),
        ]);
    }
}
