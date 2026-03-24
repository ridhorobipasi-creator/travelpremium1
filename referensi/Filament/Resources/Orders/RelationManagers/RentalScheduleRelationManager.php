<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class RentalScheduleRelationManager extends RelationManager
{
    protected static string $relationship = 'rentalSchedule';

    protected static ?string $title = 'Jadwal Rental Mobil';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('car_rental_id')
                    ->relationship('carRental', 'name')
                    ->required(),
                Forms\Components\DateTimePicker::make('start_date')
                    ->required(),
                Forms\Components\DateTimePicker::make('end_date')
                    ->required(),
                Forms\Components\TextInput::make('rental_days')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('rental_status')
                    ->options([
                        'booked' => 'Dipesan',
                        'ongoing' => 'Sedang Berjalan',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('start_date')
            ->columns([
                Tables\Columns\TextColumn::make('carRental.name')
                    ->label('Mobil'),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('rental_days')
                    ->suffix(' hari'),
                Tables\Columns\BadgeColumn::make('rental_status')
                    ->colors([
                        'info' => 'booked',
                        'warning' => 'ongoing',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make(),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
