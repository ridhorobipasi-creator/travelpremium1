<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Schemas;
use Filament\Resources\Resource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Interaksi User';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationLabel = 'Ulasan / Review';

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Informasi Ulasan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('customer_name')->required()->maxLength(255),
                        Forms\Components\TextInput::make('customer_email')->email()->maxLength(255),
                        Forms\Components\TextInput::make('rating')->numeric()->step('0.1'),
                        Forms\Components\Textarea::make('comment')->rows(4)->columnSpanFull(),
                    ]),
                Schemas\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('gallery_images')
                            ->label('Foto Gallery Review')
                            ->image()
                            ->disk('public')
                            ->directory('reviews/gallery')
                            ->visibility('public')
                            ->multiple()
                            ->maxFiles(6)
                            ->maxSize(5120)
                            ->reorderable()
                            ->columnSpanFull(),
                    ]),
                Schemas\Components\Section::make('Pengaturan')
                    ->schema([
                        Forms\Components\Toggle::make('is_approved')->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->label('Product')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('customer_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('rating')->sortable(),
                Tables\Columns\IconColumn::make('is_approved')->boolean()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->since()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_approved'),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => !$record->is_approved)
                    ->action(fn ($record) => $record->update(['is_approved' => true])),
                EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
