<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Schemas;
use Filament\Resources\Resource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Katalog Produk';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Informasi Kategori')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('icon')->maxLength(255),
                        Forms\Components\Textarea::make('description')->rows(3)->columnSpanFull(),
                    ]),
                Schemas\Components\Section::make('SEO')
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')->maxLength(255)->columnSpanFull(),
                        Forms\Components\Textarea::make('meta_description')->rows(2)->columnSpanFull(),
                    ]),
                Schemas\Components\Section::make('Pengaturan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')->numeric(),
                        Forms\Components\Toggle::make('is_active')->default(true),
                    ]),

                // ─────────────────────────────────────────────────────────────
                // TERJEMAHAN (Bahasa Asing)
                // ─────────────────────────────────────────────────────────────
                Schemas\Components\Section::make('Terjemahan (Bahasa Asing)')
                    ->description('Isi konten dalam bahasa Inggris dan Melayu.')
                    ->collapsed()
                    ->schema([
                        Schemas\Components\Tabs::make('Translations')
                            ->tabs([
                                Schemas\Components\Tabs\Tab::make('English (EN)')
                                    ->schema([
                                        Forms\Components\TextInput::make('translations.en.name')->label('Nama Kategori (EN)'),
                                        Forms\Components\Textarea::make('translations.en.description')->label('Deskripsi (EN)'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('Malaysia (MS)')
                                    ->schema([
                                        Forms\Components\TextInput::make('translations.ms.name')->label('Nama Kategori (MS)'),
                                        Forms\Components\Textarea::make('translations.ms.description')->label('Deskripsi (MS)'),
                                    ]),
                            ])->columnSpanFull()
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean()->sortable(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
