<?php

namespace App\Filament\Resources\PackageRentalScheduleResource\Pages;

use App\Filament\Resources\PackageRentalScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPackageRentalSchedule extends EditRecord
{
    protected static string $resource = PackageRentalScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
