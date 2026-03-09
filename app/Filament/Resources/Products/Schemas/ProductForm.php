<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Resources\Brands\Schemas\BrandForm;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;


class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([

                // 📌 1) GENEL BİLGİLER
            
                Section::make('Genel Bilgiler')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name_tr', fn ($q) => $q?->where('is_active', true))
                            ->searchable()
                            ->nullable()
                            ->preload()
                            ->createOptionForm(CategoryForm::getSchema())
                            ->editOptionForm(CategoryForm::getSchema())
                            ->helperText('Boş bırakılırsa "Diğer" kategorisinde listelenir'),

                        Select::make('brand_id')
                            ->label('Marka')
                            ->relationship('brand', 'name', fn ($q) => $q?->where('is_active', true))
                            ->searchable()
                            ->nullable()
                            ->preload()
                            ->createOptionForm(BrandForm::getSchema())
                            ->editOptionForm(BrandForm::getSchema()),

                        TextInput::make('sku')
                            ->label('Model Kodu (SKU)')
                            ->maxLength(255),

                        FileUpload::make('thumbnail')
                            ->label('Ana Görsel')
                            ->image()
                            ->disk('public')
                            ->directory('products/thumbnails')
                            ->imageEditor()
                            ->maxSize(5120)
                            ->nullable(),

                        Grid::make(3)
                            ->schema([
                                Toggle::make('is_featured')
                                    ->label('Öne Çıkan (Home)')
                                    ->default(false),

                                Toggle::make('is_new')
                                    ->label('Yeni')
                                    ->default(false),

                                Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true),
                            ]),

                        TextInput::make('sort_order')
                            ->label('Sıra')
                            ->numeric()
                            ->default(0),
                    ]),

                // 📌 2) DİL SEKMELERİ (TR / EN)
                Tabs::make('translations')
                    ->columnSpanFull()
                    ->tabs([

                        // 🇹🇷 TÜRKÇE
                        Tab::make('Türkçe')
                        ->icon('heroicon-o-sparkles')
                            ->schema([
                                TextInput::make('name_tr')
                                    ->label('Ürün Adı')
                                    ->required()
                                    ->maxLength(255)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug_tr', Str::slug($state)))
                                    ->live(onBlur:true),


                                TextInput::make('slug_tr')
                                    ->label('Slug ')
                                    ->required()
                                    ->maxLength(255)
                                    ->disabledOn('edit')
                                    ->dehydrated()
                                    ->unique(ignoreRecord: true),

                                TextInput::make('subtitle_tr')
                                    ->label('Alt Başlık')
                                    ->maxLength(255),

                                Textarea::make('short_description_tr')
                                    ->label('Kısa Açıklama')
                                    ->rows(3),

                                RichEditor::make('description_tr')
                                    ->label('Detay Açıklama')
                                    ->columnSpanFull(),

                                TextInput::make('seo_title_tr')
                                    ->label('SEO Title')
                                    ->maxLength(255),

                                Textarea::make('seo_description_tr')
                                    ->label('SEO Description')
                                    ->rows(2),
                            ]),

                        // 🇬🇧 ENGLISH
                        Tab::make('English')
                            ->schema([
                                TextInput::make('name_en')
                                    ->label('Product Name')
                                    ->maxLength(255)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug_en', Str::slug($state)))
                                    ->live(onBlur:true),

                                TextInput::make('slug_en')
                                    ->label('Slug')
                                    ->maxLength(255)
                                    ->disabledOn('edit')
                                    ->dehydrated()
                                    ->unique(ignoreRecord: true),

                                TextInput::make('subtitle_en')
                                    ->label('Subtitle')
                                    ->maxLength(255),

                                Textarea::make('short_description_en')
                                    ->label('Short Description')
                                    ->rows(3),

                                RichEditor::make('description_en')
                                    ->label('Detail Description')
                                    ->columnSpanFull(),

                                TextInput::make('seo_title_en')
                                    ->label('SEO Title')
                                    ->maxLength(255),

                                Textarea::make('seo_description_en')
                                    ->label('SEO Description')
                                    ->rows(2),
                            ]),
                    ]),

                // 📌 3) MEDYA (GÖRSEL & VİDEO)
                Section::make('Medya')
                    ->description('Ürün için görseller ve videolar ekleyin.')
                    ->schema([
                        Repeater::make('media')
                            ->relationship('media') // Product::media()
                            ->collapsible()
                            ->cloneable()
                            ->defaultItems(0)
                            ->schema([
                                Select::make('media_type')
                                    ->label('Tür')
                                    ->options([
                                        'image' => 'Görsel',
                                        'video' => 'Video',
                                    ])
                                    ->required()
                                    ->default('image'),

                                FileUpload::make('path')
                                    ->label('Dosya')
                                    ->disk('public')
                                    ->directory('products/media')
                                    ->imageEditor()
                                    ->maxSize(5120)
                                    ->nullable(),

                                TextInput::make('alt_text')
                                    ->label('Alt Metin / Başlık')
                                    ->maxLength(255),

                                Toggle::make('is_main')
                                    ->label('Ana Medya')
                                    ->default(false),

                                TextInput::make('sort_order')
                                    ->label('Sıra')
                                    ->numeric()
                                    ->default(0),
                            ]),
                    ]),

                // 📌 4) TEKNİK ÖZELLİKLER
                Section::make('Teknik Özellikler')
                    ->description('Saw blade capacity, motor power gibi satır satır özellikler.')
                    ->schema([
                        Repeater::make('specifications')
                            ->relationship('specifications') // Product::specifications()
                            ->orderable('sort_order')
                            ->collapsible()
                            ->defaultItems(0)
                            ->schema([
                                TextInput::make('group')
                                    ->label('Grup')
                                    ->placeholder('Capacity, Motor, Dimensions...')
                                    ->maxLength(255),

                                TextInput::make('name')
                                    ->label('Özellik Adı')
                                    ->placeholder('Saw blade capacity')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('value')
                                    ->label('Değer')
                                    ->placeholder('460')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('unit')
                                    ->label('Birim')
                                    ->placeholder('mm, kW')
                                    ->maxLength(50),

                                TextInput::make('sort_order')
                                    ->label('Sıra')
                                    ->numeric()
                                    ->default(0),
                            ]),
                    ]),

                // 📌 5) DOKÜMANLAR (E-KATALOG, BROŞÜR, MANUAL)
                Section::make('Dokümanlar')
                    ->description('E-katalog, broşür, kullanıcı kılavuzu gibi dosyalar.')
                    ->schema([
                        Repeater::make('documents')
                            ->relationship('documents') // Product::documents()
                            ->orderable('sort_order')
                            ->collapsible()
                            ->defaultItems(0)
                            ->schema([
                                TextInput::make('title')
                                    ->label('Başlık')
                                    ->placeholder('E-Catalog (TR)')
                                    ->required()
                                    ->maxLength(255),

                                FileUpload::make('file_path')
                                    ->label('Dosya')
                                    ->disk('public')
                                    ->directory('products/documents')
                                    ->maxSize(10240)
                                    ->required(),

                                Select::make('type')
                                    ->label('Tür')
                                    ->options([
                                        'ecatalog' => 'E-Catalog',
                                        'brochure' => 'Brochure',
                                        'manual' => 'User Manual',
                                        'other' => 'Other',
                                    ])
                                    ->nullable(),

                                Select::make('language_code')
                                    ->label('Dil')
                                    ->options([
                                        'tr' => 'Türkçe',
                                        'en' => 'English',
                                    ])
                                    ->nullable(),

                                TextInput::make('sort_order')
                                    ->label('Sıra')
                                    ->numeric()
                                    ->default(0),
                            ]),
                    ]),
            ]);
    }
}