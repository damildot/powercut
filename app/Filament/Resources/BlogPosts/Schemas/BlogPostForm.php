<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TagsInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Resources\BlogCategories\Schemas\BlogCategoryForm;
use Illuminate\Support\Str;

class BlogPostForm
{
    public static function getSchema(): array
    {
        return [
            // Left Column Container
            Grid::make(1)
                ->schema([
                    // Main Content Section
                    Section::make('İçerik')
                        ->schema([
                            Tabs::make('Languages')
                        ->tabs([
                            Tab::make('Türkçe')
                                ->icon('heroicon-o-language')
                                ->schema([
                                    TextInput::make('title_tr')
                                        ->label('Başlık (TR)')
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
                                        ->helperText('Başlıktan otomatik oluşturulur. Düzenleme modunda değiştirilemez.'),

                                    Textarea::make('excerpt_tr')
                                        ->label('Özet (TR)')
                                        ->rows(3)
                                        ->maxLength(300)
                                        ->helperText('Blog listesinde görünecek kısa özet.'),

                                    RichEditor::make('content_tr')
                                        ->label('İçerik (TR)')
                                        ->required()
                                        ->toolbarButtons([
                                            'bold',
                                            'italic',
                                            'underline',
                                            'strike',
                                            'h2',
                                            'h3',
                                            'bulletList',
                                            'orderedList',
                                            'link',
                                            'blockquote',
                                            'codeBlock',
                                            'undo',
                                            'redo',
                                        ])
                                        ->columnSpanFull(),
                                ]),

                            Tab::make('English')
                                ->icon('heroicon-o-globe-alt')
                                ->schema([
                                    TextInput::make('title_en')
                                        ->label('Title (EN)')
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug_en', Str::slug($state ?? ''))),

                                    TextInput::make('slug_en')
                                        ->label('SEO Slug (EN)')
                                        ->maxLength(255)
                                        ->unique(ignoreRecord: true)
                                        ->readOnly(fn (string $operation): bool => $operation === 'edit')
                                        ->dehydrated()
                                        ->helperText('Auto-generated from title. Read-only on edit.'),

                                    Textarea::make('excerpt_en')
                                        ->label('Excerpt (EN)')
                                        ->rows(3)
                                        ->maxLength(300)
                                        ->helperText('Short summary shown in blog listing.'),

                                    RichEditor::make('content_en')
                                        ->label('Content (EN)')
                                        ->toolbarButtons([
                                            'bold',
                                            'italic',
                                            'underline',
                                            'strike',
                                            'h2',
                                            'h3',
                                            'bulletList',
                                            'orderedList',
                                            'link',
                                            'blockquote',
                                            'codeBlock',
                                            'undo',
                                            'redo',
                                        ])
                                        ->columnSpanFull(),
                                ]),
                        ]),
                        ]),

                    // SEO Section (Right after İçerik)
                    Section::make('SEO Ayarları')
                        ->icon('heroicon-o-magnifying-glass')
                        ->description('Arama motoru optimizasyonu için önemli alanlar.')
                        ->schema([
                            Tabs::make('SEO')
                                ->tabs([
                                    Tab::make('SEO (TR)')
                                        ->schema([
                                            TextInput::make('seo_title_tr')
                                                ->label('Meta Başlık (TR)')
                                                ->maxLength(60)
                                                ->helperText('Google\'da görünecek başlık. Boş bırakılırsa yazı başlığı kullanılır.'),

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
                                                ->helperText('Title shown in Google. Uses post title if empty.'),

                                            Textarea::make('seo_description_en')
                                                ->label('Meta Description (EN)')
                                                ->rows(2)
                                                ->maxLength(160)
                                                ->helperText('Description shown in search results.'),
                                        ]),
                                ]),
                        ])
                        ->collapsible()
                        ->collapsed(),
                ])
                ->columnSpan(['lg' => 2]),

            // Sidebar Sections (Full Width on Mobile, 1/3 on Desktop)
            Grid::make(1)
                ->schema([
                    Section::make('Yayın')
                        ->schema([
                            Toggle::make('is_active')
                                ->label('Yayında')
                                ->default(true)
                                ->inline(false)
                                ->helperText('Pasif yazılar sitede görünmez.'),

                            Toggle::make('is_featured')
                                ->label('Öne Çıkan')
                                ->default(false)
                                ->inline(false)
                                ->helperText('Ana sayfada öne çıkar.'),

                            DateTimePicker::make('published_at')
                                ->label('Yayın Tarihi')
                                ->default(now())
                                ->required()
                                ->native(false)
                                ->helperText('Gelecekteki tarih: Zamanlanmış yayın.'),

                            TextInput::make('sort_order')
                                ->label('Sıralama')
                                ->numeric()
                                ->default(0)
                                ->helperText('Küçük sayılar önce görünür.'),
                        ]),

                    Section::make('Kategori & Yazar')
                        ->schema([
                            Select::make('blog_category_id')
                                ->label('Kategori')
                                ->relationship(name: 'category', titleAttribute: 'name_tr')
                                ->searchable()
                                ->preload()
                                ->createOptionForm(BlogCategoryForm::getSchema())
                                ->helperText('Kategori eklemek için + işaretine tıklayın.'),

                            Select::make('user_id')
                                ->label('Yazar')
                                ->relationship(name: 'author', titleAttribute: 'name')
                                ->searchable()
                                ->preload()
                                ->helperText('Boş bırakılırsa otomatik olarak sizin adınız yazılır.'),

                            TagsInput::make('tags')
                                ->label('Etiketler (TR)')
                                ->placeholder('Etiket eklemek için Enter')
                                ->helperText('Türkçe etiketler. SEO için önemli.'),

                            TagsInput::make('tags_en')
                                ->label('Tags (EN)')
                                ->placeholder('Add tag and press Enter')
                                ->helperText('English tags for the EN version of the blog.'),
                        ]),

                    Section::make('Öne Çıkan Görsel')
                        ->schema([
                            FileUpload::make('image')
                                ->label('Görsel')
                                ->image()
                                ->disk('public')
                                ->directory('blog/images')
                                ->imageEditor()
                                ->maxSize(5120)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                                ->helperText('Önerilen: 1200x630px, maks. 5MB (JPEG, PNG, WebP)'),

                            TextInput::make('image_alt_tr')
                                ->label('Görsel Alt Metni (TR)')
                                ->maxLength(255)
                                ->helperText('SEO için önemli.'),

                            TextInput::make('image_alt_en')
                                ->label('Image Alt Text (EN)')
                                ->maxLength(255)
                                ->helperText('Important for SEO.'),
                        ])
                        ->collapsible(),

                    Section::make('İstatistikler')
                        ->schema([
                            TextInput::make('reading_time')
                                ->label('Okuma Süresi (dk)')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(false)
                                ->helperText('Otomatik hesaplanır.'),

                            TextInput::make('views_count')
                                ->label('Görüntülenme')
                                ->numeric()
                                ->default(0)
                                ->disabled()
                                ->dehydrated(false)
                                ->helperText('Ziyaretçi sayısı.'),
                        ])
                        ->hidden(fn(string $operation): bool => $operation === 'create')
                        ->collapsible()
                        ->collapsed(),
                ])
                ->columnSpan(['lg' => 1]),
        ];
    }
}
