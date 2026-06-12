<?php

namespace App\Filament\Resources\Queries\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section; // Asegúrate de importar Section de Infolists si deseas estructurarlo
use Filament\Schemas\Schema;

class QueryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Datos del Solicitante
                TextEntry::make('name')
                    ->label('Nombre/Apodo'),

                TextEntry::make('email')
                    ->label('Correo Electrónico')
                    ->copyable(), // Permite al administrador copiar el email con un clic

                // Mapeo nativo del asunto numérico a texto para visualización
                TextEntry::make('subject')
                    ->label('Asunto / Motivo')
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'Formas de pago',
                        2 => 'Modos/costos de envío',
                        3 => 'Devolución',
                        4 => 'Cuenta',
                        5 => 'Otros',
                        default => 'No especificado',
                    })
                    ->columnSpanFull(),

                // El estado en una vista Infolist se visualiza mejor como una etiqueta de color (Badge)
                TextEntry::make('status')
                    ->label('Estado de la Consulta')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'danger',      // Rojo
                        'processing' => 'warning',   // Amarillo
                        'resolved' => 'success',    // Verde
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendiente 🔴',
                        'processing' => 'En revisión 🟡',
                        'resolved' => 'Resuelta 🟢',
                        default => $state,
                    }),

                TextEntry::make('message')
                    ->label('Mensaje Recibido')
                    ->columnSpanFull(),

                // Fechas formateadas automáticamente para la zona horaria de Argentina
                TextEntry::make('created_at')
                    ->label('Fecha de Recepción')
                    ->dateTime('d/m/Y H:i', 'America/Argentina/Buenos_Aires')
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->label('Última Actualización')
                    ->dateTime('d/m/Y H:i', 'America/Argentina/Buenos_Aires')
                    ->placeholder('-'),
            ]);
    }
}
