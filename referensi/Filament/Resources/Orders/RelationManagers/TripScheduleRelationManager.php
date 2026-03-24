<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class TripScheduleRelationManager extends RelationManager
{
    protected static string $relationship = 'tripSchedule';

    protected static ?string $title = 'Jadwal Trip';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('vehicle_id')
                    ->relationship('vehicle', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('driver_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('driver_phone')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\DatePicker::make('trip_date')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('scheduled'),
                Forms\Components\Textarea::make('notes')
                    ->rows(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('trip_date')
            ->columns([
                Tables\Columns\TextColumn::make('trip_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('driver_name')
                    ->label('Driver'),
                Tables\Columns\TextColumn::make('vehicle.name')
                    ->label('Kendaraan'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'info' => 'scheduled',
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
