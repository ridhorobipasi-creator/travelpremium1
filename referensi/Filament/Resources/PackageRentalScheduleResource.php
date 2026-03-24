<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageRentalScheduleResource\Pages;
use App\Models\PackageRentalSchedule;
use Filament\Forms;
use Filament\Schemas;
use Filament\Resources\Resource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;

class PackageRentalScheduleResource extends Resource
{
    protected static ?string $model = PackageRentalSchedule::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Pesanan & Jadwal';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Jadwal Paket Rental';
    
    protected static ?int $navigationSort = 4;

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Informasi Paket')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('order_id')
                            ->label('ID Pesanan (Opsional)')
                            ->relationship('order', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "#ORD-" . str_pad($record->id, 5, '0', STR_PAD_LEFT) . " ({$record->customer_name})")
                            ->searchable()
                            ->preload()
                            ->columnSpanFull()
                            ->helperText('Hubungkan jadwal ini dengan pesanan pelanggan jika ada.'),
                        Forms\Components\Select::make('rental_package_id')
                            ->label('Paket Rental')
                            ->relationship('rentalPackage', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('customer_name')
                            ->label('Nama Pelanggan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('customer_phone')
                            ->label('No. Telepon')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('customer_email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                    ]),
                
                Schemas\Components\Section::make('Jadwal & Durasi')
                    ->columns(3)
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->required()
                            ->native(false),
                        Forms\Components\DateTimePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->required()
                            ->native(false)
                            ->after('start_date'),
                        Forms\Components\TextInput::make('rental_days')
                            ->label('Jumlah Hari')
                            ->numeric()
                            ->required()
                            ->default(1),
                    ]),
                
                Schemas\Components\Section::make('Detail Tambahan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('pickup_location')
                            ->label('Lokasi Penjemputan')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('destination')
                            ->label('Tujuan')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('number_of_people')
                            ->label('Jumlah Orang')
                            ->numeric()
                            ->default(1),
                        Forms\Components\Textarea::make('special_requests')
                            ->label('Permintaan Khusus')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                
                Schemas\Components\Section::make('Harga & Status')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('total_price')
                            ->label('Total Harga')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        Forms\Components\Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Lunas',
                                'partial' => 'DP',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('pending')
                            ->required(),
                        Forms\Components\Select::make('booking_status')
                            ->label('Status Booking')
                            ->options([
                                'confirmed' => 'Dikonfirmasi',
                                'ongoing' => 'Sedang Berjalan',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('confirmed')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rentalPackage.name')
                    ->label('Paket')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_phone')
                    ->label('Telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Mulai')
                    ->dateTime('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Selesai')
                    ->dateTime('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('rental_days')
                    ->label('Hari')
                    ->suffix(' hari')
                    ->sortable(),
                Tables\Columns\TextColumn::make('number_of_people')
                    ->label('Peserta')
                    ->suffix(' orang')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total')
                    ->money('IDR', true)
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'info' => 'partial',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\BadgeColumn::make('booking_status')
                    ->label('Status')
                    ->colors([
                        'info' => 'confirmed',
                        'warning' => 'ongoing',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Lunas',
                        'partial' => 'DP',
                        'cancelled' => 'Dibatalkan',
                    ]),
                Tables\Filters\SelectFilter::make('booking_status')
                    ->label('Status Booking')
                    ->options([
                        'confirmed' => 'Dikonfirmasi',
                        'ongoing' => 'Sedang Berjalan',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
                Tables\Filters\Filter::make('start_date_month')
                    ->form([
                        Forms\Components\Select::make('month')
                            ->label('Bulan')
                            ->options([
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                            ]),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['month'], fn ($query, $month) => $query->whereMonth('start_date', $month));
                    }),
                Tables\Filters\Filter::make('start_date_year')
                    ->form([
                        Forms\Components\Select::make('year')
                            ->label('Tahun')
                            ->options(array_combine(range(now()->year, now()->year - 5), range(now()->year, now()->year - 5)))
                            ->default(now()->year),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['year'], fn ($query, $year) => $query->whereYear('start_date', $year));
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('start_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackageRentalSchedules::route('/'),
            'create' => Pages\CreatePackageRentalSchedule::route('/create'),
            'view' => Pages\ViewPackageRentalSchedule::route('/{record}'),
            'edit' => Pages\EditPackageRentalSchedule::route('/{record}/edit'),
        ];
    }
}
