<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use RowAction;
use Filament\Actions\RowAction\EditRowAction;
use Filament\Actions\RowAction\DeleteRowAction;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name_tr')
                    ->label('Kategori')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('Diğer'),

                TextColumn::make('name_tr')
                    ->label('Ürün')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sku')
                    ->label('Model')
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_featured')
                    ->label('Öne çıkan')
                    ->boolean()
                    ->toggleable(),


                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->toggleable(),

                TextColumn::make('sort_order')
                    ->label('Sıra')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                //
            ])
            ->recordActions([  EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}