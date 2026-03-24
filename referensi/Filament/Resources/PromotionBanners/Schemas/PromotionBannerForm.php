<?php

namespace App\Filament\Resources\PromotionBanners\Schemas;

use Filament\Schemas\Schema;

class PromotionBannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\FileUpload::make('image_url')
                    ->image()
                    ->disk('public')
                    ->directory('banners')
                    ->visibility('public')
                    ->required(),
                \Filament\Forms\Components\TextInput::make('link_url')
                    ->maxLength(255),
                \Filament\Forms\Components\Select::make('position')
                    ->options([
                        'home_top' => 'Home Top',
                        'home_middle' => 'Home Middle',
                        'sidebar' => 'Sidebar',
                    ]),
                \Filament\Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
