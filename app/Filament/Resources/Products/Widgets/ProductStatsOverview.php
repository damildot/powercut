<?php

namespace App\Filament\Resources\Products\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Product;

class ProductStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Toplam Ürün', Product::count())
                ->description('Kayıtlı tüm makineler')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),
            
            Stat::make('Yayındaki Ürünler', Product::where('is_active', true)->count())
                ->description('Web sitesinde görünenler')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success'),

            Stat::make('Vitrin Ürünleri', Product::where('is_featured', true)->count())
                ->description('Anasayfada öne çıkanlar')
                ->color('warning'),
        ];
    }
}
