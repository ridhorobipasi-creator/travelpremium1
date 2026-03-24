<?php

namespace App\Filament\Resources\InstagramFeeds\Schemas;

use Filament\Schemas\Schema;

class InstagramFeedForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('post_id')
                    ->maxLength(255),
                \Filament\Forms\Components\FileUpload::make('image_url')
                    ->image()
                    ->disk('public')
                    ->directory('instagram')
                    ->visibility('public')
                    ->required(),
                \Filament\Forms\Components\Textarea::make('caption')
                    ->rows(3),
                \Filament\Forms\Components\TextInput::make('permalink')
                    ->url()
                    ->maxLength(255),
                \Filament\Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
