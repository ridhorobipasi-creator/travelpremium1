<?php

namespace App\Filament\Resources\InstagramFeeds\Pages;

use App\Filament\Resources\InstagramFeeds\InstagramFeedResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditInstagramFeed extends EditRecord
{
    protected static string $resource = InstagramFeedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
