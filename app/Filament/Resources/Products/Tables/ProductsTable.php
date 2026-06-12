<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Support\Facades\Storage;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_1')
                    ->label('Imagen')
                    ->disk('public')
                    ->circular()
                    ->url(fn($record) => $record->image_1 ? Storage::url($record->image_1) : null, shouldOpenInNewTab: true),

                // Información principal
                TextColumn::make('title')
                    ->label('Titulo')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('brand.name')
                    ->label('Marca')
                    ->searchable()
                    ->sortable(),

                // Detectar el precio final:
                // Apunta al accesor final_price. Muestra el precio con descuento si está en oferta
                TextColumn::make('final_price')
                    ->label('Precio Actual')
                    ->money('ARS')
                    ->sortable(['price']) // Fuerza a la base de datos a ordenar usando la columna física 'price'(el precio de lista es el criterio valido)
                    ->alignEnd()
                    // Si está en oferta ('on_sale' es true), el texto resalta en verde, si no queda gris estándar
                    ->color(fn($record) => $record->on_sale ? 'success' : 'gray')
                    ->weight(fn($record) => $record->on_sale ? 'bold' : 'normal')
                    // Si hay descuento, dibuja al administrador el precio original de lista debajo
                    ->description(function ($record) {
                        if ($record->on_sale && $record->discount > 0) {
                            return 'Antes: $' . number_format($record->price, 2, ',', '.');
                        }
                        return null;
                    }),

                TextColumn::make('stock')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('views')
                    ->label('Nro de vistas')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                CheckboxColumn::make('active')
                    ->label('Activo'),

                IconColumn::make('on_sale')
                    ->label('Oferta')
                    ->boolean()
                    ->trueIcon('heroicon-o-tag')
                    ->falseIcon('heroicon-o-x-circle')
                    ->color(fn(bool $state): string => $state ? 'success' : 'gray'),

                TextColumn::make('discount')
                    ->label('% Desc.')
                    ->suffix('%')
                    // Hacemos que la columna de descuento evalúe de forma segura usando la fila actual
                    ->visible(fn($record) => $record?->on_sale),

                TextColumn::make('created_at')
                    ->label('Fecha en que fue agregado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // FILTRO DE RELACIÓN: Categoría
                SelectFilter::make('category_id')
                    ->label('ID De Categoría')
                    ->relationship('category', 'name') // 'category' es el método en modelo Product
                    ->label('Filtrar por Categoría')
                    ->preload()
                    ->searchable(),

                // FILTRO DE RELACIÓN: Marca
                SelectFilter::make('brand_id')
                    ->relationship('brand', 'name')
                    ->label('Filtrar por Marca')
                    ->preload(),

                // FILTRO BOOLEANO: Oferta (on_sale)
                TernaryFilter::make('on_sale')
                    ->label('¿En Liquidación?')
                    ->placeholder('Todos los productos')
                    ->trueLabel('Solo en Oferta')
                    ->falseLabel('Precio Normal'),

                // FILTRO BOOLEANO: Activo (active)
                TernaryFilter::make('active')
                    ->label('Disponibilidad')
                    ->boolean(),

                //FILTRO ELIMINADOS
                TrashedFilter::make(),
            ])

            ->recordActions([
                // Esta acción se mostrará/ejecutará SOLO si el producto NO está eliminado
                EditAction::make()
                    ->visible(fn($record) => ! $record->trashed()),
                DeleteAction::make()
                    ->visible(fn($record) => !$record->trashed()),

                // Esta acción se mostrará/ejecutará SOLO si el producto SÍ está eliminado
                RestoreAction::make()
                    ->visible(fn($record) => $record->trashed()),
                //FORCEDELETE POR SI SE DESEA USAR...
                /*ForceDeleteAction::make()
                    ->visible(fn($record) => $record->trashed())
                    ->label('Borrado definitivo')
                    ->modalHeading('¿Estás absolutamente seguro?')
                    ->modalDescription('No se puede deshacer. El registro se perderá para siempre.'), */
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
