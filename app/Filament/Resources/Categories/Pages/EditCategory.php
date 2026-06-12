<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('verWeb')
                ->label('Ver en la tienda')
                ->color('gray')
                ->icon('heroicon-o-eye')
                ->url(fn(): string => route('catalog', [
                    'categoria' => $this->record->id // Pasa el ID de la categoría actual
                ]))
                ->openUrlInNewTab()
                ->disabled(fn($record) => $record->trashed())
                ->color(fn($record) => $record->trashed() ? 'gray' : 'primary'),

            DeleteAction::make(),
            RestoreAction::make(),

        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
