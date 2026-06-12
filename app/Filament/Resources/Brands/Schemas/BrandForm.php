<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // Usar una Sección para agrupar campos relacionados
                Section::make('Información General')
                    ->description('Configura los datos básicos de la marca.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre de la marca')
                            ->required()
                            ->unique() // o ->unique(ignoreRecord: true)
                            ->columnSpan(1),

                        Toggle::make('active')
                            ->label('¿Marca activa?')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2), // Divide la sección en 2 columnas
            ]);
    }
}
