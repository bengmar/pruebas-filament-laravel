<?php

namespace App\Filament\Resources\Queries\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        //Acá manejo los detalles del usuario que deseo que se visualicen en "VER". Abajo se añadirá automáticamente la relación con sus órdenes.
            ->components([
                ComponentsSection::make('Información del Usuario')
                ->columns(2)
                ->schema([
                    TextEntry::make('name')
                        ->label('Nombre Completo'),

                    TextEntry::make('email')
                        ->label('Correo Electrónico'),

                    TextEntry::make('created_at')
                        ->label('Miembro desde')
                        ->dateTime('d/m/Y'),
                ]),
            ]);
    }
}
