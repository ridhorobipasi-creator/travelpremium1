<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class PackageRentalScheduleRelationManager extends RelationManager
{
    protected static string $relationship = 'packageRentalSchedule';

    protected static ?string $title = 'Jadwal Paket Rental';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('rental_package_id')
                    ->relationship('rentalPackage', 'name')
                    ->required(),
                Forms\Components\DateTimePicker::make('start_date')
                    ->required(),
                Forms\Components\DateTimePicker::make('end_date')
                    ->required(),
                Forms\Components\Select::make('booking_status')
                    ->options([
                        'confirmed' => 'Dikonfirmasi',
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
                Tables\Columns\TextColumn::make('rentalPackage.name')
                    ->label('Paket'),
                Tables\Columns\TextColumn::make('start_date')
                    ->date(),
                Tables\Columns\BadgeColumn::make('booking_status')
                    ->colors([
                        'info' => 'confirmed',
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
