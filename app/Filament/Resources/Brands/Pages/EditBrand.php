<?php

namespace App\Filament\Resources\Brands\Pages;

use App\Filament\Resources\Brands\BrandResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditBrand extends EditRecord
{
    protected static string $resource = BrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('verWeb')
                ->label('Ver en tienda')
                ->color(fn (): string => (
                    !$this->record->active ||
                    $this->record->trashed()
                ) ? 'gray' : 'primary')
                ->icon('heroicon-o-eye')
                ->url(function (): ?string {
                    // Si el botón termina deshabilitado por las reglas de abajo,
                    // devolvemos null para que no intente generar un enlace roto.
                    if (
                        empty($this->record->name) ||
                        strlen($this->record->name) < 3 ||
                        !$this->record->active ||
                        $this->record->trashed()
                    ) {
                        return null;
                    }
                    return route('search', ['query' => $this->record->name]);
                })
                ->disabled(function (): bool {
                    // El botón se bloquea (disabled) si cumple cualquiera de estas condiciones:
                    return empty($this->record->name) ||
                        strlen($this->record->name) < 3 ||
                        !$this->record->active ||       // 1. La marca está desactivada
                        $this->record->trashed();       // 2. La marca sufrió un Soft Delete (está en papelera)
                })
                ->openUrlInNewTab(),

            DeleteAction::make(),
            RestoreAction::make()
        ];
    }
}
