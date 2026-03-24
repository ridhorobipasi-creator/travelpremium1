<?php

namespace App\Filament\Resources\CarRentalResource\Pages;

use App\Filament\Resources\CarRentalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCarRental extends EditRecord
{
    protected static string $resource = CarRentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
