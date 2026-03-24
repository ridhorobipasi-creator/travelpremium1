<?php

namespace App\Filament\Resources\Galleries\Schemas;

use Filament\Schemas\Schema;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('title')
                    ->maxLength(255),
                \Filament\Forms\Components\FileUpload::make('image_url')
                    ->image()
                    ->disk('public')
                    ->directory('galleries')
                    ->visibility('public')
                    ->required(),
                \Filament\Forms\Components\Textarea::make('caption')
                    ->rows(2)
                    ->maxLength(500),
                \Filament\Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                \Filament\Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
