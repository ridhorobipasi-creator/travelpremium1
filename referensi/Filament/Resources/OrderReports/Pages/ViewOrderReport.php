<?php

namespace App\Filament\Resources\OrderReports\Pages;

use App\Filament\Resources\OrderReports\OrderReportResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrderReport extends ViewRecord
{
    protected static string $resource = OrderReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
