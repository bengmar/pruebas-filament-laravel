<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Acción para ver el producto en la parte pública
            Action::make('ver_tienda')
                ->label('Ver en Tienda')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn(): string => route('product-details', ['id' => $this->record->id]))
                ->openUrlInNewTab()
                //Desactivo el botón si el producto no está activo o está con soft deletes
                ->disabled(fn ($record) => $record->trashed() || !$record->active || !$record->brand?->active)
                // Color a gris para que se note que está apagado
                ->color(fn($record) => $record->trashed() || !$record->active || !$record->brand?->active? 'gray' : 'primary'),
            RestoreAction::make(),
            DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
