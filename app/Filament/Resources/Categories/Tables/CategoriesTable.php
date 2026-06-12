<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nombre de la Categoría')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('display_title')
                    ->label('Título Largo/Comercial')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('products_count')
                    ->label('Cant. Productos')
                    // Importante: Por defecto cuenta los productos asociados vigentes.
                    ->counts('products')
                    ->badge()
                    ->color('info')
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Creada el')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Este filtro le permite al usuario alternar entre activas, borradas o todas
                TrashedFilter::make(),
            ])
            ->recordActions([
                // Esta acción se mostrará/ejecutará SOLO si la categoría no está eliminada
                EditAction::make()
                    ->visible(fn($record) => !$record->trashed()),
                DeleteAction::make()
                    ->visible(fn($record) => !$record->trashed()),

                // Esta acción se mostrará/ejecutará SOLO si la categoría está eliminada (softdelete)
                RestoreAction::make()
                    ->visible(fn($record) => $record->trashed()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // Elimina usando Soft Delete (llena la columna deleted_at)
                    DeleteBulkAction::make(),

                    // Permite restaurar categorías borradas desde el filtro "Trash"
                    RestoreBulkAction::make(),

                    // Eliminado permanente. Desactivado
                    //ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}

