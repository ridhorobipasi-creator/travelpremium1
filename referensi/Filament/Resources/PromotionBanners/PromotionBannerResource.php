<?php

namespace App\Filament\Resources\PromotionBanners;

use App\Filament\Resources\PromotionBanners\Pages\CreatePromotionBanner;
use App\Filament\Resources\PromotionBanners\Pages\EditPromotionBanner;
use App\Filament\Resources\PromotionBanners\Pages\ListPromotionBanners;
use App\Filament\Resources\PromotionBanners\Schemas\PromotionBannerForm;
use App\Filament\Resources\PromotionBanners\Tables\PromotionBannersTable;
use App\Models\PromotionBanner;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PromotionBannerResource extends Resource
{
    protected static ?string $model = PromotionBanner::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static string|\UnitEnum|null $navigationGroup = 'Konten Website';

    protected static ?string $navigationLabel = 'Banner Promosi';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return PromotionBannerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PromotionBannersTable::configure($table);
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
            'index' => ListPromotionBanners::route('/'),
            'create' => CreatePromotionBanner::route('/create'),
            'edit' => EditPromotionBanner::route('/{record}/edit'),
        ];
    }
}
