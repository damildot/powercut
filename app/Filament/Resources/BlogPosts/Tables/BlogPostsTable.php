<?php

namespace App\Filament\Resources\BlogPosts\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkAction;
use Illuminate\Support\Collection;

class BlogPostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Görsel')
                    ->circular()
                    ->defaultImageUrl(url('/assets/img/default-blog.jpg')),

                TextColumn::make('title_tr')
                    ->label('Başlık (TR)')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(50),

                TextColumn::make('category.name_tr')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('author.name')
                    ->label('Yazar')
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_featured')
                    ->label('Öne Çıkan')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label('Yayın Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->color(fn($record) => $record->published_at > now() ? 'warning' : 'success')
                    ->icon(fn($record) => $record->published_at > now() ? 'heroicon-o-clock' : 'heroicon-o-check-circle'),

                TextColumn::make('views_count')
                    ->label('Görüntülenme')
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

                TextColumn::make('reading_time')
                    ->label('Okuma')
                    ->suffix(' dk')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                SelectFilter::make('blog_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name_tr')
                    ->preload(),

                SelectFilter::make('user_id')
                    ->label('Yazar')
                    ->relationship('author', 'name')
                    ->preload(),

                TernaryFilter::make('is_active')
                    ->label('Durum')
                    ->placeholder('Tümü')
                    ->trueLabel('Aktif')
                    ->falseLabel('Pasif'),

                TernaryFilter::make('is_featured')
                    ->label('Öne Çıkan')
                    ->placeholder('Tümü')
                    ->trueLabel('Evet')
                    ->falseLabel('Hayır'),

                Filter::make('published')
                    ->label('Yayın Durumu')
                    ->query(fn(Builder $query): Builder => $query->where('published_at', '<=', now()))
                    ->toggle(),

                Filter::make('scheduled')
                    ->label('Zamanlanmış')
                    ->query(fn(Builder $query): Builder => $query->where('published_at', '>', now()))
                    ->toggle(),
            ])
           ->recordActions([
                ViewAction::make()
                    ->label('Görüntüle'),
                EditAction::make()
                    ->label('Düzenle'),
           ])
           ->toolbarActions([
                BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each->delete())
                    ->label('Toplu Sil')
                    ->deselectRecordsAfterCompletion(),
           ]);



    }
}

