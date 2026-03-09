<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Models\Setting;
use Filament\Schemas\Components\Tabs\Tab;
class ListSettings extends ListRecords
{
    protected static string $resource = SettingResource::class;

    //Sayfa yüklenirken çalışır
    public function mount(): void
    {
        // İlk kaydı bul
        $setting = Setting::first();

        if (! $setting) {
            $setting = Setting::create([
              
            ]);
        }

        redirect(SettingResource::getUrl('edit', ['record' => $setting->id]));
    }

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

   

}
