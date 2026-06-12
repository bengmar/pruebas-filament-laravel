<?php

namespace App\Filament\Resources\Queries\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section; // Asegúrate de importar Section de Schemas en Filament 5
use Filament\Schemas\Schema;

class QueryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Sección 1: Datos de la consulta original (Solo Lectura)
                Section::make('Detalle de la Consulta')
                    ->description('Información enviada por el usuario desde la web.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre del Solicitante')
                            ->disabled()
                            ->dehydrated(false), // Evita enviar datos deshabilitados al servidor

                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->disabled()
                            ->dehydrated(false),

                        // Vinculamos directamente el select al campo 'subject' de la base de datos
                        Select::make('subject')
                            ->label('Asunto / Motivo')
                            ->options([
                                1 => 'Formas de pago',
                                2 => 'Modos/costos de envío',
                                3 => 'Devolución',
                                4 => 'Cuenta',
                                5 => 'Otros',
                            ])
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        Textarea::make('message')
                            ->label('Mensaje Recibido')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                // Sección 2: Control Interno del Administrador (Editable)
                Section::make('Gestión de la Solicitud')
                    ->schema([
                        Select::make('status')
                            ->label('Estado de la Consulta')
                            ->options([
                                'pending' => 'Pendiente 🔴',
                                'processing' => 'En revisión 🟡',
                                'resolved' => 'Resuelta 🟢',
                            ])
                            ->required()
                            ->selectablePlaceholder(false)
                    ])
            ]);
    }
}
