<?php

namespace App\Filament\Resources\RentalScheduleResource\Pages;

use App\Filament\Resources\RentalScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRentalSchedule extends EditRecord
{
    protected static string $resource = RentalScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
