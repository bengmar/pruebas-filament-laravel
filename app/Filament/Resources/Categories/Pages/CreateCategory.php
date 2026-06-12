<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
    // Cambiar el título de la página
    protected ?string $heading = 'Registrar Nueva Categoría';

    // Personalizar el texto del botón principal
    protected static ?string $title = 'Crear Categoría';

    // Redirigir al listado después de crear (por defecto te lleva al Edit)
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Añadir una notificación personalizada al terminar
    protected function getCreatedNotificationTitle(): ?string
    {
        return '¡La categoría ha sido registrada con éxito!';
    }
}
