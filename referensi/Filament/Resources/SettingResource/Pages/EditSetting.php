<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use Filament\Resources\Pages\EditRecord;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    protected function afterSave(): void
    {
        \Illuminate\Support\Facades\Cache::forget('site_settings');
        \Illuminate\Support\Facades\Cache::forget('app_settings');
    }
}
