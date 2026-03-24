<?php

namespace App\Filament\Resources\TripData\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TripDataForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informasi Umum')
                ->columns(3)
                ->schema([
                    Select::make('bulan')
                        ->label('Bulan')
                        ->options([
                            'Januari'   => 'Januari',
                            'Februari'  => 'Februari',
                            'Maret'     => 'Maret',
                            'April'     => 'April',
                            'Mei'       => 'Mei',
                            'Juni'      => 'Juni',
                            'Juli'      => 'Juli',
                            'Agustus'   => 'Agustus',
                            'September' => 'September',
                            'Oktober'   => 'Oktober',
                            'November'  => 'November',
                            'Desember'  => 'Desember',
                        ])
                        ->required()
                        ->default('Januari'),

                    DatePicker::make('tanggal')
                        ->label('Tanggal')
                        ->nullable(),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending'   => 'Belum Konfirmasi',
                            'confirmed' => 'Terkonfirmasi',
                            'ongoing'   => 'Berlangsung',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                        ])
                        ->default('pending')
                        ->required(),
                ]),

            Section::make('Data Pelanggan')
                ->columns(2)
                ->schema([
                    TextInput::make('nama_pelanggan')
                        ->label('Nama Pelanggan')
                        ->nullable(),

                    TextInput::make('nomor_hp')
                        ->label('Nomor HP / WA')
                        ->tel()
                        ->nullable(),
                ]),

            Section::make('Detail Perjalanan')
                ->columns(3)
                ->schema([
                    TextInput::make('layanan')
                        ->label('Layanan')
                        ->nullable(),

                    TextInput::make('jumlah_hari')
                        ->label('Jumlah Hari')
                        ->numeric()
                        ->nullable(),

                    TextInput::make('penumpang')
                        ->label('Penumpang')
                        ->nullable(),

                    Toggle::make('drone')
                        ->label('Gunakan Drone')
                        ->default(false),
                ]),

            Section::make('Kendaraan & Driver')
                ->columns(3)
                ->schema([
                    TextInput::make('nama_driver')
                        ->label('Nama Driver')
                        ->nullable(),

                    TextInput::make('plat_mobil')
                        ->label('Plat Mobil')
                        ->nullable(),

                    TextInput::make('jenis_mobil')
                        ->label('Jenis Mobil')
                        ->nullable(),
                ]),

            Section::make('Hotel')
                ->columns(2)
                ->schema([
                    TextInput::make('hotel_1')->label('Hotel 1')->nullable(),
                    TextInput::make('hotel_2')->label('Hotel 2')->nullable(),
                    TextInput::make('hotel_3')->label('Hotel 3')->nullable(),
                    TextInput::make('hotel_4')->label('Hotel 4')->nullable(),
                ]),

            Section::make('Pembayaran')
                ->columns(3)
                ->schema([
                    TextInput::make('harga')
                        ->label('Harga')
                        ->numeric()
                        ->prefix('Rp')
                        ->nullable(),

                    TextInput::make('deposit')
                        ->label('Deposit')
                        ->numeric()
                        ->prefix('Rp')
                        ->nullable(),

                    TextInput::make('pelunasan')
                        ->label('Pelunasan')
                        ->numeric()
                        ->prefix('Rp')
                        ->nullable(),
                ]),

            Section::make('Penerbangan')
                ->columns(2)
                ->schema([
                    TextInput::make('tiba')
                        ->label('Tiba (Flight Datang)')
                        ->nullable(),

                    TextInput::make('flight_balik')
                        ->label('Flight Balik')
                        ->nullable(),
                ]),
        ]);
    }
}
