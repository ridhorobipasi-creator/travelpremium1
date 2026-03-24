<?php

namespace App\Filament\Resources\CustomRequests;

use App\Filament\Resources\CustomRequests\Pages\EditCustomRequest;
use App\Filament\Resources\CustomRequests\Pages\ListCustomRequests;
use App\Filament\Resources\CustomRequests\Pages\ViewCustomRequest;
use App\Filament\Resources\CustomRequests\Tables\CustomRequestsTable;
use App\Models\CustomRequest;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;

class CustomRequestResource extends Resource
{
    protected static ?string $model = CustomRequest::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Custom Trip Requests';

    protected static string|UnitEnum|null $navigationGroup = 'Pesanan & Jadwal';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'customer_name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Status & Catatan Admin')
                ->schema([
                    Select::make('status')
                        ->options([
                            'new'       => 'Baru',
                            'reviewed'  => 'Ditinjau',
                            'responded' => 'Ditanggapi',
                            'closed'    => 'Selesai',
                        ])
                        ->required(),
                    Textarea::make('admin_notes')
                        ->label('Catatan Admin')
                        ->rows(4)
                        ->placeholder('Catatan internal untuk request ini...'),
                ])->columns(1),

            Section::make('Data Pelanggan')
                ->schema([
                    TextInput::make('customer_name')->label('Nama')->disabled(),
                    TextInput::make('customer_phone')->label('No. HP')->disabled(),
                    TextInput::make('customer_email')->label('Email')->disabled(),
                ])->columns(3),

            Section::make('Detail Trip')
                ->schema([
                    DatePicker::make('trip_date')->label('Tanggal Trip')->disabled(),
                    TextInput::make('trip_duration')->label('Durasi (hari)')->disabled(),
                    TextInput::make('num_persons')->label('Jumlah Orang')->disabled(),
                    Textarea::make('destinations')->label('Destinasi')->disabled()->columnSpanFull(),
                ])->columns(3),

            Section::make('Preferensi')
                ->schema([
                    TextInput::make('budget_range')->label('Budget')->disabled(),
                    TextInput::make('accommodation_type')->label('Akomodasi')->disabled(),
                    TextInput::make('transport_type')->label('Transportasi')->disabled(),
                    Textarea::make('special_requests')->label('Permintaan Khusus')->disabled()->columnSpanFull(),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return CustomRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCustomRequests::route('/'),
            'view'   => ViewCustomRequest::route('/{record}'),
            'edit'   => EditCustomRequest::route('/{record}/edit'),
        ];
    }
}
