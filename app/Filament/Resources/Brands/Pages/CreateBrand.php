<?php

namespace App\Filament\Resources\Brands\Pages;

use App\Filament\Resources\Brands\BrandResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBrand extends CreateRecord
{
    protected static string $resource = BrandResource::class;


    // Cambiar el título de la página
    protected ?string $heading = 'Registrar Nueva Marca';

    // Personalizar el texto del botón principal
    protected static ?string $title = 'Crear Marca';

    // Redirigir al listado después de crear (por defecto te lleva al Edit)
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Añadir una notificación personalizada al terminar
    protected function getCreatedNotificationTitle(): ?string
    {
        return '¡La marca ha sido registrada con éxito!';
    }

    // Ejecuta lógica antes de crear el registro
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ejemplo: Asegurar que el nombre siempre se guarde en mayúsculas
        //$data['name'] = strtoupper($data['name']);

        // Ejemplo: Asignar el ID del usuario que crea la marca si fuera necesario
        // $data['user_id'] = auth()->id();

        return $data;
    }

    // Ejecuta lógica justo después de crear
    protected function afterCreate(): void
    {
        // Ejemplo: Enviar un log o disparar un evento de sistema
        // Log::info("Nueva marca creada: " . $this->record->name);
    }
}
