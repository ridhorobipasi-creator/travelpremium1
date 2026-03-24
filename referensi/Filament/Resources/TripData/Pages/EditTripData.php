<?php

namespace App\Filament\Resources\TripData\Pages;

use App\Filament\Resources\TripData\TripDataResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTripData extends EditRecord
{
    protected static string $resource = TripDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
