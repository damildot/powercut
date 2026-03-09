<?php

namespace App\Filament\Resources\Categories\Schemas;


use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;



class CategoryForm
{
    public static function getSchema(): array
    {
        return 
            [

                // 📌 BÖLÜM: GENEL AYARLAR
                Section::make('Genel Ayarlar')
                    ->columnSpanFull() 
                    ->schema([
                     /*   FileUpload::make('image')
                            ->label('Kategori Görseli')
                            ->image()
                            ->directory('categories')
                            ->imageEditor()
                            ->nullable(),*/

                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Status')
                                    ->default(true),

                                Toggle::make('show_on_home')
                                    ->label('Anasayfada Göster')
                                    ->default(false),
                            ]),

                        TextInput::make('sort_order')
                            ->label('Sıra')
                            ->numeric()
                            ->default(0),
                    ])->collapsible(true),

                // 📌 BÖLÜM: DİL SEKMELERİ
                Tabs::make('translations')
                    ->columnSpanFull()
                    ->tabs([

                        // 🇹🇷 TÜRKÇE TAB
                        Tab::make('Türkçe')
                            ->schema([
                                TextInput::make('name_tr')
                                    ->label('Ad')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug_tr', Str::slug((string) $state)))
                                    ->required(),
                                    

                                TextInput::make('slug_tr')
                                    ->label('Slug')
                                    ->disabledOn('edit')
                                    ->unique(ignoreRecord: true)
                                    ->dehydrated()
                                    ->required(),

                                TextInput::make('subtitle_tr')
                                    ->label('Alt Başlık'),

                                Textarea::make('description_tr')
                                    ->label('Açıklama')
                                    ->rows(4)
                                    ->columnSpanFull(),

                                TextInput::make('seo_title_tr')
                                    ->label('SEO Başlık'),

                                Textarea::make('seo_description_tr')
                                    ->label('SEO Açıklama')
                                    ->rows(2),
                            ]),

                        // 🇬🇧 İNGİLİZCE TAB
                        Tab::make('English')
                            ->schema([
                                TextInput::make('name_en')
                                    ->label('Name')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug_en', Str::slug((string) $state)))
                                    ->required(),

                                TextInput::make('slug_en')
                                    ->label('Slug')
                                    ->disabledOn('edit')
                                    ->unique(ignoreRecord: true)
                                    ->dehydrated()
                                    ->required(),

                                TextInput::make('subtitle_en')
                                    ->label('Subtitle'),

                                Textarea::make('description_en')
                                    ->label('Description')
                                    ->rows(4)
                                    ->columnSpanFull(),

                                TextInput::make('seo_title_en')
                                    ->label('SEO Title'),

                                Textarea::make('seo_description_en')
                                    ->label('SEO Description')
                                    ->rows(2),
                            ]),
                    ]),
            ];
    }
}
