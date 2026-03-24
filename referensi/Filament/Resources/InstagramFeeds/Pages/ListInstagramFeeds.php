<?php

namespace App\Filament\Resources\InstagramFeeds\Pages;

use App\Filament\Resources\InstagramFeeds\InstagramFeedResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInstagramFeeds extends ListRecords
{
    protected static string $resource = InstagramFeedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
