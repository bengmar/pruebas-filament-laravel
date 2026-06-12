<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use App\Http\Requests\UserRequest; // Importado UserRequest
use Filament\Facades\Filament;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        // Instancia del Request para extraer el array de reglas base.

        $requestRules = (new UserRequest())->rules();

        return $schema
            ->components([
                TextInput::make('last_name')
                    ->label('Apellido/s')
                    ->required()
                    ->string()
                    ->rules($requestRules['last_name']) // Regla del Request
                    ->disabled(fn($record) => $record !== null && Filament::auth()->id() !== $record->id)
                    ->dehydrated(fn($record) => $record === null || Filament::auth()->id() === $record->id)
                    ->maxLength(255),
                //NOTA: el atributo ->hiddenOn('view') por ej, me permitía ocultar detalles en la vista view. Ya no lo uso por manejar todo en Infolist
                TextInput::make('first_name')
                    ->label('Nombre/s')
                    ->required()
                    ->string()
                    ->rules($requestRules['first_name']) // Regla del Request
                    ->disabled(fn($record) => $record !== null && Filament::auth()->id() !== $record->id)
                    ->dehydrated(fn($record) => $record === null || Filament::auth()->id() === $record->id)
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->email()
                    ->required()
                    ->string()
                    ->maxLength(255)
                    ->disabled(fn($record) => $record !== null && Filament::auth()->id() !== $record->id)
                    ->dehydrated(fn($record) => $record === null || Filament::auth()->id() === $record->id)
                    // Lógica de Filament para ignorar el registro actual
                    ->unique(table: 'users', column: 'email', ignoreRecord: true),

                DateTimePicker::make('email_verified_at')
                    ->label('Correo verificado el día')
                    ->timezone('America/Argentina/Buenos_Aires'),

                Select::make('role_id')
                    ->label('Rol')
                    ->relationship('role', 'name')
                    ->required()
                    ->preload()
                    ->rules($requestRules['role_id']) // Regla del Request. Asegura que se valide contra 'exists:roles,id'
                    ->disabled(fn($record): bool => $record !== null && Filament::auth()->id() === $record->id)
                    ->dehydrated(fn($state) => filled($state)),
                //Aqui solo me manejo con la validación del lado de filament, para que no hayan conflictos en campos vacíos.
                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->revealable()
                    // Obligatorio solo al crear
                    ->required(fn(string $operation): bool => $operation === 'create')
                    // Estas reglas solo se aplicarán si el usuario escribió algo (gracias a 'nullable')
                    ->nullable() //cuando el required no está activo, automáticamente el nullable ignora las otras reglas
                    ->string()
                    ->minLength(8)
                    ->confirmed()
                    // Solo guardamos el campo en la base de datos si el usuario escribió algo
                    ->dehydrated(fn($state) => filled($state)),

                TextInput::make('password_confirmation')
                    ->label('Confirmar Contraseña')
                    ->password()
                    ->revealable()
                    // Obligatorio solo si se está creando, o si estás editando y el campo password tiene texto
                    ->required(fn(string $operation, $get): bool => $operation === 'create' || filled($get('password')))
                    // Evitamos que se envíe a la base de datos ya que solo sirve para validar
                    ->dehydrated(false),
            ]);
    }
}
