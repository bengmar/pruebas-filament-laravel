<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    // Cambio del título de la página
    public function getTitle(): string
    {
        return "Editar Orden #" . $this->getRecord()->id;
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
            ->label("Volver"),
            //DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
