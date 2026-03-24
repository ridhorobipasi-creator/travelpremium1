<?php

namespace App\Filament\Resources\TripSchedules\Pages;

use App\Filament\Resources\TripSchedules\TripScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTripSchedule extends EditRecord
{
    protected static string $resource = TripScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
