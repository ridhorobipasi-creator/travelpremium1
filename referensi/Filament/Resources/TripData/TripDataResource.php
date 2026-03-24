<?php

namespace App\Filament\Resources\TripData;

use App\Filament\Resources\TripData\Pages\CreateTripData;
use App\Filament\Resources\TripData\Pages\EditTripData;
use App\Filament\Resources\TripData\Pages\ListTripData;
use App\Filament\Resources\TripData\Schemas\TripDataForm;
use App\Filament\Resources\TripData\Tables\TripDataTable;
use App\Models\Trip;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class TripDataResource extends Resource
{
    protected static ?string $model = Trip::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-table-cells';

    protected static string|UnitEnum|null $navigationGroup = 'Sistem & Pengaturan';

    protected static ?string $navigationLabel = 'Data Trip';

    protected static ?string $modelLabel = 'Trip';

    protected static ?string $pluralModelLabel = 'Data Trip';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return TripDataForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TripDataTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListTripData::route('/'),
            'create' => CreateTripData::route('/create'),
            'edit'   => EditTripData::route('/{record}/edit'),
        ];
    }
}
