<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Products\Pages\ViewUser;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $relatedResource = OrderResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                //CreateAction::make(),
            ]);
    }
    public static function canViewForRecord($record, $pageClass): bool
    {

        // 1ero: Que solo se muestre en la página de "Ver" (lo que ya tenías)
        $isViewPage = $pageClass === ViewUser::class;

        // 2do: Que el usuario evaluado ($record) NO sea un administrador
        $isNotAdminRecord = $record->role->name !== 'admin';

        // El Relation Manager solo se cargará si se cumplen ambas condiciones
        return $isViewPage && $isNotAdminRecord;
    }
}
