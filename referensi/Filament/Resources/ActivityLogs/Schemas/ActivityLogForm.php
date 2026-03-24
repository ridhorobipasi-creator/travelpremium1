<?php

namespace App\Filament\Resources\ActivityLogs\Schemas;

use Filament\Schemas\Schema;

class ActivityLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('description')
                    ->disabled(),
                \Filament\Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->disabled(),
                \Filament\Forms\Components\TextInput::make('ip_address')
                    ->disabled(),
                \Filament\Forms\Components\KeyValue::make('properties')
                    ->disabled(),
                \Filament\Forms\Components\DateTimePicker::make('created_at')
                    ->disabled(),
            ]);
    }
}
