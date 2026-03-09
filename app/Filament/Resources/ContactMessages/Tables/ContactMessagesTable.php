<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use App\Filament\Resources\ContactMessages\Schemas\ContactMessageInfolist;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()->label('Gönderen'),
                TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable()->label('Telefon'),
                TextColumn::make('subject')
                    ->searchable()->label('Konu'),
                TextColumn::make('status')
                    ->searchable()->label('Durum'),
                TextColumn::make('read_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()->label('Okunma Tarihi'),
                TextColumn::make('source')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Kaynak'),
                TextColumn::make('language_code')
                    ->searchable()->label('Dil')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ip_address')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('IP Adresi'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)->label('Oluşturulma Tarihi'),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)->label('Güncellenme Tarihi'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Görüntüle')
                    ->modalHeading('Mesaj Detayı')
                    ->modalWidth('4xl')
                    ->slideOver()
                    ->schema(fn (Schema $schema) => ContactMessageInfolist::configure($schema)),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
