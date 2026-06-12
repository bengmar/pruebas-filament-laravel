<?php

namespace App\Filament\Resources\Queries;

use App\Filament\Resources\Queries\Pages\CreateQuery;
use App\Filament\Resources\Queries\Pages\EditQuery;
use App\Filament\Resources\Queries\Pages\ListQueries;
use App\Filament\Resources\Queries\Pages\ViewQuery;
use App\Filament\Resources\Queries\Schemas\QueryForm;
use App\Filament\Resources\Queries\Schemas\QueryInfolist;
use App\Filament\Resources\Queries\Tables\QueriesTable;
use App\Models\Query;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QueryResource extends Resource
{
    protected static ?string $model = Query::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleOvalLeftEllipsis;

    protected static ?string $recordTitleAttribute = 'name';

    // Evita que aparezca el botón "Crear" en el panel si el usuario intenta forzar la acción - política que afecta solo a filament
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return QueryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return QueryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QueriesTable::configure($table);
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
            'index' => ListQueries::route('/'),
            //'create' => CreateQuery::route('/create'),
            'view' => ViewQuery::route('/{record}'),
            'edit' => EditQuery::route('/{record}/edit'),
        ];
    }
    // Nombre en la barra lateral
    protected static ?string $navigationLabel = 'Consultas';

    // Título de la página (en plural)
    protected static ?string $pluralLabel = 'Consultas';

    // Título para un solo registro
    protected static ?string $modelLabel = 'Consulta';
}
