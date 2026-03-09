<?php

namespace App\Filament\Resources\BlogCategories\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Str;
use Filament\Forms\Components\TextInput;



class BlogCategoryForm
{
    public static function getSchema(): array
    {
        return [
            // Single column layout for modal
            Section::make()
                ->schema([
                    // Multi-language Tabs
                    Tabs::make('Languages')
                        ->tabs([
                            Tab::make('Türkçe')
                                ->icon('heroicon-o-language')
                                ->schema([
                                            TextInput::make('name_tr')
                                                ->label('Kategori Adı (TR)')
                                                ->required()
                                                ->maxLength(255)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn ($state, callable $set) => $set('slug_tr', Str::slug($state))),

                                            TextInput::make('slug_tr')
                                                ->label('SEO Slug (TR)')
                                                ->maxLength(255)
                                                ->unique(ignoreRecord: true)
                                                ->readOnly(fn (string $operation): bool => $operation === 'edit')
                                                ->dehydrated()
                                                ->helperText('İsimden otomatik oluşturulur. Düzenleme modunda değiştirilemez.'),

                                            Textarea::make('description_tr')
                                                ->label('Açıklama (TR)')
                                                ->rows(3)
                                                ->maxLength(500)
                                                ->helperText('Kategori listesinde görünecek kısa açıklama.'),
                                        ]),

                                    Tab::make('English')
                                        ->icon('heroicon-o-globe-alt')
                                        ->schema([
                                            TextInput::make('name_en')
                                                ->label('Category Name (EN)')
                                                ->maxLength(255)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn ($state, callable $set) => $set('slug_en', Str::slug($state ?? ''))),

                                            TextInput::make('slug_en')
                                                ->label('SEO Slug (EN)')
                                                ->maxLength(255)
                                                ->unique(ignoreRecord: true)
                                                ->readOnly(fn (string $operation): bool => $operation === 'edit')
                                                ->dehydrated()
                                                ->helperText('Auto-generated from name. Read-only on edit.'),

                                            Textarea::make('description_en')
                                                ->label('Description (EN)')
                                                ->rows(3)
                                                ->maxLength(500)
                                        ->helperText('Short description for category listing.'),
                                ]),
                        ]),
                ]),

            // Settings Section
            Section::make('Durum ve Ayarlar')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Toggle::make('is_active')
                                ->label('Aktif')
                                ->default(true)
                                ->helperText('Pasif kategoriler sitede görünmez.'),

                            TextInput::make('sort_order')
                                ->label('Sıralama')
                                ->numeric()
                                ->default(0)
                                ->helperText('Küçük sayılar önce görünür.'),
                        ]),
                ]),

            // SEO Section
            Section::make('SEO Ayarları')
                ->icon('heroicon-o-magnifying-glass')
                ->description('Arama motoru optimizasyonu için önemli alanlar.')
                ->collapsible()
                ->collapsed()
                ->schema([
                    Tabs::make('SEO')
                        ->tabs([
                            Tab::make('SEO (TR)')
                                ->schema([
                                    TextInput::make('seo_title_tr')
                                        ->label('Meta Başlık (TR)')
                                        ->maxLength(60)
                                        ->helperText('Google\'da görünecek başlık. Boş bırakılırsa kategori adı kullanılır.'),

                                    Textarea::make('seo_description_tr')
                                        ->label('Meta Açıklama (TR)')
                                        ->rows(2)
                                        ->maxLength(160)
                                        ->helperText('Arama sonuçlarında görünecek açıklama.'),
                                ]),

                            Tab::make('SEO (EN)')
                                ->schema([
                                    TextInput::make('seo_title_en')
                                        ->label('Meta Title (EN)')
                                        ->maxLength(60)
                                        ->helperText('Title shown in Google. Uses category name if empty.'),

                                    Textarea::make('seo_description_en')
                                        ->label('Meta Description (EN)')
                                        ->rows(2)
                                        ->maxLength(160)
                                        ->helperText('Description shown in search results.'),
                                ]),
                        ]),
                ]),
        ];
    }
}

