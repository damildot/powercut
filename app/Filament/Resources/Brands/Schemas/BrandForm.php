<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class BrandForm
{
    /**
     * Form schema is returned as an array so it can be reused in popups/modals.
     */
    public static function getSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Marka Adı')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),
            TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true)
                ->disabled(fn (?\App\Models\Brand $record) => $record !== null)
                ->dehydrated()
                ->helperText('SEO güvenliği için URL oluşturulduktan sonra değiştirilemez.'),
                    Section::make()
                        ->schema([
                            FileUpload::make('logo')
                                ->label('Logo')
                                ->directory('brands')
                                ->disk('public')
                                ->visibility('public')
                                ->imageEditor()
                                ->image(),

                     
                            Toggle::make('is_active')
                                ->default(true)
                                ->gap(false)
                                ->label('Status'),
                                ])
                        
                    ,
                


        ];
    }
}
