<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Products\Pages\ViewUser;
use App\Filament\Resources\Queries\Schemas\UserInfolist;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\RelationManagers\OrdersRelationManager;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder; // Importante para el query global
use Illuminate\Database\Eloquent\SoftDeletingScope; // Importante para Soft Deletes


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    // Como 'name' es el accesor (last_name + first_name), Filament lo entiende para títulos de registros globales.
    protected static ?string $recordTitleAttribute = 'name';

    //Para consulta a la base de datos, es como ejecutar:
    //SELECT * FROM `users`
    //WHERE (`users`.`first_name` LIKE '%yam%' OR `users`.`last_name` LIKE '%yam%')
    //LIMIT 50
    public static function getGloballySearchableAttributes(): array
    {
        return ['first_name', 'last_name'];
    }

    // Rectángulos
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }
    public static function infolist(Schema $schema): Schema
    {
        return UserInfolist::configure($schema); //Agregado el Infolist
    }

    public static function getRelations(): array
    {
        return [
            OrdersRelationManager::class, // Agregada la relación con sus órdenes
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    // Soporte nativo para Soft Deletes en las consultas globales de Filament
    // Esto permite que el panel pueda leer, editar o restaurar usuarios eliminados correctamente.
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('role') // Precarga de los roles, para evitar N+1
            ->withoutGlobalScopes([
                SoftDeletingScope::class, //permitiendo Soft Deletes
            ]);
    }

    // Nombre en la barra lateral
    protected static ?string $navigationLabel = 'Usuarios';

    // Título de la página (en plural)
    protected static ?string $pluralLabel = 'Usuarios';

    // Título para un solo registro (ej: "Crear Usuario")
    protected static ?string $modelLabel = 'Usuario';
}
