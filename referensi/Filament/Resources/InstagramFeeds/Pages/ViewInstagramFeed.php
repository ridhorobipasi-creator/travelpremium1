<?php

namespace App\Filament\Resources\InstagramFeeds\Pages;

use App\Filament\Resources\InstagramFeeds\InstagramFeedResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInstagramFeed extends ViewRecord
{
    protected static string $resource = InstagramFeedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
