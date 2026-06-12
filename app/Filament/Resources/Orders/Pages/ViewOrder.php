<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    // Título de la página de visualización
    public function getTitle(): string
    {
        return "Detalle de la Orden #" . $this->getRecord()->id;
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
            ->label('Cambiar Estado'),
        ];
    }
}
