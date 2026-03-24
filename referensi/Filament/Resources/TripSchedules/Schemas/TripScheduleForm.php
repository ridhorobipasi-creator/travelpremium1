<?php

namespace App\Filament\Resources\TripSchedules\Schemas;

use Filament\Schemas\Schema;

class TripScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('order_id')
                    ->relationship('order', 'id') // Usually customized to show customer name
                    ->getOptionLabelFromRecordUsing(fn ($record) => "#{$record->id} - {$record->customer_name}")
                    ->searchable()
                    ->required(),
                \Filament\Forms\Components\Select::make('vehicle_id')
                    ->relationship('vehicle', 'name')
                    ->searchable()
                    ->preload(),
                \Filament\Forms\Components\TextInput::make('driver_name')
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('driver_phone')
                    ->tel()
                    ->maxLength(20),
                \Filament\Forms\Components\DatePicker::make('trip_date')
                    ->required(),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('scheduled'),
                \Filament\Forms\Components\Textarea::make('notes')
                    ->rows(3),
            ]);
    }
}
