<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter; // Filtro de Soft Deletes
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('last_name')
                    ->label('Apellido/s')
                    ->searchable(),

                TextColumn::make('first_name')
                    ->label('Nombre/s')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable(),

                TextColumn::make('email_verified_at')
                    ->label('Fecha de verif. de correo')
                    ->dateTime('d/m/Y H:i', 'America/Argentina/Buenos_Aires')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('role.name')
                    ->label('Rol')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Fecha de creación')
                    ->dateTime('d/m/Y H:i', 'America/Argentina/Buenos_Aires')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Última actualización')
                    ->dateTime('d/m/Y H:i', 'America/Argentina/Buenos_Aires')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Al usar SoftDeletes en el modelo User, este filtro permite al admin
                // alternar entre usuarios activos, eliminados o ver todos.
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(), // Chequear!
                // Esta acción se mostrará/ejecutará SOLO si el usuario NO está eliminado
                EditAction::make()
                    ->visible(fn($record) => !$record->trashed()),
                DeleteAction::make()
                    ->visible(fn($record) => !$record->trashed()),

                // Esta acción se mostrará/ejecutará SOLO si el usuario SÍ está eliminado
                RestoreAction::make()
                    ->visible(fn($record) => $record->trashed()),
                /* ForceDeleteAction::make()
                    ->visible(fn($record) => $record->trashed())
                    ->label('Borrado definitivo')
                    ->modalHeading('¿Estás absolutamente seguro?')
                    ->modalDescription('No se puede deshacer. El registro se perderá para siempre.') */
            ]);
    }
}
