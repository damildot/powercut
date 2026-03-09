<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
             //   ImageEntry::make('image')
         //           ->placeholder('-'),
                IconEntry::make('is_active')
                    ->boolean(),
                IconEntry::make('show_on_home')
                    ->boolean(),
                TextEntry::make('sort_order')
                    ->numeric(),
                TextEntry::make('name_tr'),
                TextEntry::make('slug_tr'),
                TextEntry::make('subtitle_tr')
                    ->placeholder('-'),
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
