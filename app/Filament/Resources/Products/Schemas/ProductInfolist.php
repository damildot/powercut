<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('category_id')
                    ->numeric(),
                TextEntry::make('sku')
                    ->label('SKU')
                    ->placeholder('-'),
                TextEntry::make('thumbnail')
                    ->placeholder('-'),
                IconEntry::make('is_featured')
                    ->boolean(),
                IconEntry::make('is_new')
                    ->boolean(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('sort_order')
                    ->numeric(),
                TextEntry::make('name_tr'),
                TextEntry::make('slug_tr'),
                TextEntry::make('subtitle_tr')
                    ->placeholder('-'),
                TextEntry::make('short_description_tr')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('description_tr')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('seo_title_tr')
                    ->placeholder('-'),
                TextEntry::make('seo_description_tr')
                    ->placeholder('-'),
                TextEntry::make('name_en')
                    ->placeholder('-'),
                TextEntry::make('slug_en')
                    ->placeholder('-'),
                TextEntry::make('subtitle_en')
                    ->placeholder('-'),
                TextEntry::make('short_description_en')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('description_en')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('seo_title_en')
                    ->placeholder('-'),
                TextEntry::make('seo_description_en')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
