<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope; // 1. Importamos el Scope de Soft Deletes

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
            'view' => Pages\ViewProduct::route('/{record}'),
        ];
    }

    //Scopes globales: el del modelo y el que permite ver detalles de productos borrados sin tirar 404
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                'active',                 // Mi lógica de ignorar productos desactivados (solo en filament admin)
                SoftDeletingScope::class, // Suma el soporte para poder ver/editar productos eliminados con Soft Delete
            ]);
    }

    // Campos que se usarán para la búsqueda global en la barra superior
    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'subtitle', 'description'];
    }

    // Personalización de lo que se ve en el resultado de búsqueda global
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Categoría' => $record->category?->name ?? 'Sin categoría', // Agregado null-safe por si la categoría fue eliminada
            'Precio' => '$' . number_format($record->price, 2),
        ];
    }

    // Nombre en la barra lateral
    protected static ?string $navigationLabel = 'Productos';

    // Título de la página (en plural)
    protected static ?string $pluralLabel = 'Productos';

    // Título para un solo registro (ej: "Crear Producto")
    protected static ?string $modelLabel = 'Producto';
}
