<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(name: 'create-product')
            ->label('Yeni')
            ->icon('heroicon-o-plus'),
        ];
    }


    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\Products\Widgets\ProductStatsOverview::class,
        ];
    }
}
