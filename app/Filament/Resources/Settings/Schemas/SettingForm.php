<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->schema([
            Tabs::make('Ayarlar')
                ->tabs([
                    // 1. SEKME: GENEL & LOGO
                    Tab::make('Genel & Logo')
                        ->schema([
                            TextInput::make('site_title')->label('Site Adı')->required(),
                            FileUpload::make('logo_light')
                                ->label('Logo (Light - Footer ve Dark Arka Plan İçin)')
                                ->image()
                                ->disk('public')->directory('settings')
                                ->helperText('Footer ve dark background için beyaz/açık renkli logo'),
                            FileUpload::make('logo_dark')
                                ->label('Logo (Dark - Header ve Light Arka Plan İçin)')
                                ->image()
                                ->disk('public')->directory('settings')
                                ->helperText('Header ve light background için koyu renkli logo'),
                            FileUpload::make('favicon')
                                ->label('Favicon')
                                ->image()
                                ->disk('public')->directory('settings'),
                        ]),

                    // 2. SEKME: İLETİŞİM & SOSYAL MEDYA
                    Tab::make('İletişim')
                        ->schema([
                            TextInput::make('email')->email()->label('E-Posta'),
                            TextInput::make('phone')->tel()->label('Telefon'),
                            TextInput::make('whatsapp_phone')->label('WhatsApp No'),
                            TextInput::make('facebook')->prefix('facebook.com/'),
                            TextInput::make('instagram')->prefix('instagram.com/'),
                            TextInput::make('linkedin')->prefix('linkedin.com/in/'),
                            TextInput::make('youtube')->prefix('youtube.com/'),
                            Textarea::make('address')->label('Adres')->rows(2),
                            Textarea::make('google_maps_embed')->label('Google Maps Embed Kodu')->rows(3),
                        ]),

                    // 3. SEKME: SLIDER YÖNETİMİ (FOTO & VİDEO)
                    Tab::make('Anasayfa Slider')
                        ->schema([
                            Repeater::make('hero_slides')
                                ->label('Slider Görselleri')
                                ->schema([
                                    Select::make('type')
                                        ->label('Tip')
                                        ->options([
                                            'image' => 'Resim',
                                            'video' => 'Video (MP4)',
                                        ])
                                        ->default('image')
                                        ->live(), // Değişince formu yenile
                                    
                                    // Tip Resim ise bunu göster
                                    FileUpload::make('image_path')
                                        ->label('Resim Dosyası')
                                        ->image()
                                        ->disk('public')->directory('slider')
                                        ->visible(fn ($get) => $get('type') === 'image'),

                                    // Tip Video ise bunu göster
                                    FileUpload::make('video_path')
                                        ->label('Video Dosyası')
                                        ->acceptedFileTypes(['video/mp4'])
                                        ->disk('public')->directory('slider')
                                        ->visible(fn ($get) => $get('type') === 'video'),

                                    TextInput::make('title_tr')->label('Başlık'),
                                    TextInput::make('title_en')->label('Title'),
                                    TextInput::make('subtitle_tr')->label('Alt Başlık'),
                                    TextInput::make('subtitle_en')->label('Subtitle'),
                                    TextInput::make('button_text_tr')->label('Buton Yazısı (TR)'),
                                    TextInput::make('button_text_en')->label('Button Text (EN)'),
                                    TextInput::make('button_link')->label('Buton Linki'),
                                ])
                                ->collapsible()
                                ->grid(1),
                        ]),

                    // 4. SEKME: SEO AYARLARI
                    Tab::make('SEO')
                        ->schema([
                            TextInput::make('seo_title_tr')->label('Meta Başlık'),
                            TextInput::make('seo_title_en')->label('Meta Title'),
                            Textarea::make('seo_description_tr')->label('Meta Açıklama'),
                            Textarea::make('seo_description_en')->label('Meta Description'),
                        ]),
                ])->columnSpanFull(),
        ]);
        
 
    }
}
