<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('plate_number')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(20),
                \Filament\Forms\Components\TextInput::make('capacity')
                    ->numeric()
                    ->minValue(1),
                \Filament\Forms\Components\Select::make('type')
                    ->options([
                        'SUV' => 'SUV',
                        'MPV' => 'MPV',
                        'Van' => 'Van',
                        'Bus' => 'Bus',
                        'Sedan' => 'Sedan',
                    ])
                    ->required(),
                \Filament\Forms\Components\TextInput::make('brand')
                    ->label('Merek')
                    ->maxLength(100),
                \Filament\Forms\Components\TextInput::make('transmission')
                    ->label('Transmisi')
                    ->maxLength(50)
                    ->placeholder('Manual / Automatic'),
                \Filament\Forms\Components\FileUpload::make('thumbnail')
                    ->label('Foto Kendaraan')
                    ->disk('public')
                    ->directory('vehicles')
                    ->visibility('public')
                    ->image(),
                \Filament\Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
