<?php

namespace App\Filament\Resources\Queries\Pages;

use App\Filament\Resources\Queries\QueryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewQuery extends ViewRecord
{
    protected static string $resource = QueryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
            ->label('Cambiar Estado'),
        ];
    }

    public function mount($record): void
    {
        // inicializamos el $this->record
        parent::mount($record);

        // Si está pendiente
        if ($this->record->status === 'pending') {

            // Actualizamos directamente en la base de datos a procesando
            $this->record->update([
                'status' => 'processing',
            ]);
        }
    }
}
