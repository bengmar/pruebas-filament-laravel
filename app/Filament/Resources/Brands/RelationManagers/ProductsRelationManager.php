<?php

namespace App\Filament\Resources\Brands\RelationManagers;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $title = 'Productos Asociados';

    protected static ?string $relatedResource = ProductResource::class;

    public function table(Table $table): Table
    {
        return $table
            // 🔒 Forzamos a Filament a ignorar el scope 'active' en esta tabla
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                'active',
            ]))
            ->filters([
                // Te recomiendo dejar el TrashedFilter por si algún producto está en la papelera
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
