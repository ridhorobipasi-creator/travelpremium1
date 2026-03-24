<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Components as Schemas;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Sistem & Pengaturan';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Kelola Pengaturan';
    protected static ?string $modelLabel = 'Pengaturan';
    protected static ?string $pluralModelLabel = 'Pengaturan';
    protected static ?int $navigationSort = 10;

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Schemas\Section::make('Detail Pengaturan')
                    ->description('Buat atau edit pengaturan dengan key dan value.')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label('Key (Kunci)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('Contoh: site_name, facebook_url, custom_setting')
                            ->regex('/^[a-z0-9_]+$/')
                            ->validationMessages([
                                'regex' => 'Key hanya boleh berisi huruf kecil, angka, dan underscore.',
                            ]),
                        Forms\Components\Select::make('group')
                            ->label('Grup Pengaturan')
                            ->options([
                                'business' => 'Informasi Bisnis',
                                'social' => 'Media Sosial',
                                'branding' => 'Branding & UI',
                                'seo' => 'SEO & Analytics',
                                'payment' => 'Pembayaran',
                                'mail' => 'Email/SMTP',
                                'content' => 'Konten Landing Page',
                                'language' => 'Bahasa & Lokalisasi',
                                'other' => 'Lainnya',
                            ])
                            ->default('other')
                            ->helperText('Grup untuk mengelompokkan pengaturan (opsional).')
                            ->dehydrated(false),
                        Forms\Components\Textarea::make('value')
                            ->label('Value (Nilai)')
                            ->rows(4)
                            ->columnSpanFull()
                            ->helperText('Untuk array/JSON, masukkan dalam format JSON. Contoh: ["item1","item2"]'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Key')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-key'),
                Tables\Columns\TextColumn::make('value')
                    ->label('Value')
                    ->searchable()
                    ->limit(60)
                    ->tooltip(function (Setting $record): ?string {
                        return strlen($record->value ?? '') > 60 ? $record->value : null;
                    }),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('business')
                    ->label('Bisnis')
                    ->query(fn ($query) => $query->whereIn('key', [
                        'site_name', 'whatsapp_number', 'site_email', 'site_address', 'working_hours', 'timezone',
                    ])),
                Tables\Filters\Filter::make('social')
                    ->label('Media Sosial')
                    ->query(fn ($query) => $query->where('key', 'like', '%_url')),
                Tables\Filters\Filter::make('branding')
                    ->label('Branding')
                    ->query(fn ($query) => $query->whereIn('key', [
                        'site_logo', 'site_favicon', 'default_hero_image', 'primary_color', 'secondary_color',
                    ])),
                Tables\Filters\Filter::make('seo')
                    ->label('SEO')
                    ->query(fn ($query) => $query->where('key', 'like', 'meta_%')->orWhere('key', 'like', 'google_%')),
                Tables\Filters\Filter::make('payment')
                    ->label('Pembayaran')
                    ->query(fn ($query) => $query->where('key', 'like', 'bank_%')->orWhere('key', 'qris_image')),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->after(function () {
                        \Illuminate\Support\Facades\Cache::forget('site_settings');
                        \Illuminate\Support\Facades\Cache::forget('app_settings');
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->after(function () {
                            \Illuminate\Support\Facades\Cache::forget('site_settings');
                            \Illuminate\Support\Facades\Cache::forget('app_settings');
                        }),
                ]),
            ])
            ->defaultSort('key', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
