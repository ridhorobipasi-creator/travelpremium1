<?php

namespace App\Filament\Resources\TripData\Pages;

use App\Filament\Resources\TripData\TripDataResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTripData extends ListRecords
{
    protected static string $resource = TripDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
