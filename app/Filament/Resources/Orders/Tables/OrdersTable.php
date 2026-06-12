<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
// Si quisiera cambiar el estado desde la tabla
// use Filament\Tables\Columns\SelectColumn;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // El ID de la Orden como identificador principal
                TextColumn::make('id')
                    ->label('Orden #')
                    ->searchable()
                    ->sortable(),

                // Nombre del usuario usando la relación 'user'
                TextColumn::make('user.name') //el name es obtenido de la función del modelo getNameAttribute()
                    ->label('Cliente')
                    // Si el usuario fue borrado definitivamente, mostrará esto:
                    ->default('Usuario Eliminado (Historial Protegido)')
                    // Busca en los campos del usuario, o en los fijos de la orden por si el usuario ya no existe
                    ->searchable(['first_name', 'last_name', 'customer_name', 'customer_lastname'])
                    ->sortable(),


                // Mostramos la Ciudad usando la relación 'city'
                TextColumn::make('city.name')
                    ->label('Ciudad')
                    ->searchable()
                    ->sortable(),

                // Formato moneda para el total (ARS)
                TextColumn::make('total')
                    ->label('Total')
                    ->money('ARS')
                    ->sortable(),

                // Método de Pago resumido
                TextColumn::make('payment_method')
                    ->label('Método de Pago')
                    ->searchable(),

                // 7. El Estado Del Envío.
                TextColumn::make('status')
                    ->label('Estado de Envío')
                    ->badge()
                    ->formatStateUsing(fn($record) => $record->status_label)
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                // Fecha de creación para saber cuándo se compró
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i', 'America/Argentina/Buenos_Aires')
                    ->sortable(),
            ])
            // Ordenar por defecto de la orden más nueva a la más vieja
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Ver Detalles'),
                EditAction::make()
                    ->label('Cambiar Estado'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}
