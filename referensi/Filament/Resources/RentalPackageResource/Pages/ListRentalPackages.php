<?php

namespace App\Filament\Resources\RentalPackageResource\Pages;

use App\Filament\Resources\RentalPackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRentalPackages extends ListRecords
{
    protected static string $resource = RentalPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
