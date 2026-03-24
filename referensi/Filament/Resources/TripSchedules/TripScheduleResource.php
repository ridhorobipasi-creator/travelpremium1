<?php

namespace App\Filament\Resources\TripSchedules;

use App\Filament\Resources\TripSchedules\Pages\CreateTripSchedule;
use App\Filament\Resources\TripSchedules\Pages\EditTripSchedule;
use App\Filament\Resources\TripSchedules\Pages\ListTripSchedules;
use App\Filament\Resources\TripSchedules\Schemas\TripScheduleForm;
use App\Filament\Resources\TripSchedules\Tables\TripSchedulesTable;
use App\Models\TripSchedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class TripScheduleResource extends Resource
{
    protected static ?string $model = TripSchedule::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|UnitEnum|null $navigationGroup = 'Pesanan & Jadwal';

    protected static ?string $navigationLabel = 'Jadwal Trip';
    
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return TripScheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TripSchedulesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTripSchedules::route('/'),
            'create' => CreateTripSchedule::route('/create'),
            'edit' => EditTripSchedule::route('/{record}/edit'),
        ];
    }
}
