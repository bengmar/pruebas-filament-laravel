<?php

namespace App\Filament\Resources\Queries\Pages;

use App\Filament\Resources\Queries\QueryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditQuery extends EditRecord
{
    protected static string $resource = QueryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
            ->label('Volver'),
            DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
