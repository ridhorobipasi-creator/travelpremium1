<?php

namespace App\Filament\Resources\RentalPackageResource\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RentalPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informasi Paket')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Nama Paket')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                ]),

            Section::make('Durasi & Harga')
                ->columns(3)
                ->schema([
                    TextInput::make('price_per_day')
                        ->label('Harga Per Hari / Pax')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),

                    TextInput::make('min_rental_days')
                        ->label('Min. Hari')
                        ->numeric()
                        ->default(1)
                        ->required(),

                    TextInput::make('max_rental_days')
                        ->label('Maks. Hari')
                        ->numeric()
                        ->nullable()
                        ->helperText('Kosongkan jika tidak ada batas'),
                ]),

            Section::make('Deskripsi')
                ->schema([
                    RichEditor::make('description')
                        ->label('Deskripsi Paket')
                        ->columnSpanFull(),
                ]),

            Section::make('Sudah Termasuk & Tidak Termasuk')
                ->columns(2)
                ->schema([
                    TagsInput::make('includes')
                        ->label('Sudah Termasuk')
                        ->placeholder('Tambah item...')
                        ->nullable(),

                    TagsInput::make('excludes')
                        ->label('Tidak Termasuk')
                        ->placeholder('Tambah item...')
                        ->nullable(),
                ]),

            Section::make('Gambar & Pengaturan')
                ->columns(3)
                ->schema([
                    FileUpload::make('featured_image')
                        ->label('Foto Paket')
                        ->image()
                        ->disk('public')
                        ->directory('rental-packages')
                        ->visibility('public')
                        ->columnSpan(2),

                    Section::make()
                        ->schema([
                            Toggle::make('is_active')
                                ->label('Aktif')
                                ->default(true),

                            TextInput::make('sort_order')
                                ->label('Urutan')
                                ->numeric()
                                ->default(0),
                        ]),
                ]),
        ]);
    }
}
