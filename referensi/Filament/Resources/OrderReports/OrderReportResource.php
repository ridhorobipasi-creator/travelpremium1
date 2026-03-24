<?php

namespace App\Filament\Resources\OrderReports;

use App\Filament\Resources\OrderReports\Pages\CreateOrderReport;
use App\Filament\Resources\OrderReports\Pages\EditOrderReport;
use App\Filament\Resources\OrderReports\Pages\ListOrderReports;
use App\Filament\Resources\OrderReports\Pages\ViewOrderReport;
use App\Filament\Resources\OrderReports\Schemas\OrderReportForm;
use App\Filament\Resources\OrderReports\Schemas\OrderReportInfolist;
use App\Filament\Resources\OrderReports\Tables\OrderReportsTable;
use App\Models\OrderReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrderReportResource extends Resource
{
    protected static ?string $model = OrderReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return OrderReportForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrderReportInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrderReportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrderReports::route('/'),
            'create' => CreateOrderReport::route('/create'),
            'view' => ViewOrderReport::route('/{record}'),
            'edit' => EditOrderReport::route('/{record}/edit'),
        ];
    }
}
