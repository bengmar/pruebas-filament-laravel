<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use App\Models\Order;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Infolists\Components\RepeatableEntry as ComponentsRepeatableEntry;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Sección: Información del Cliente
                ComponentsSection::make('Información del Cliente')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextEntry::make('customer_name')
                            ->label('Nombre'),
                        TextEntry::make('customer_lastname')
                            ->label('Apellido'),
                        TextEntry::make('customer_email')
                            ->label('Correo Electrónico'),
                    ])
                    ->collapsible(),

                // Sección: Dirección de Entrega
                ComponentsSection::make('Dirección de Entrega')
                    ->icon('heroicon-o-truck')
                    ->schema([
                        TextEntry::make('delivery_street')
                            ->label('Calle y Número'),
                        TextEntry::make('delivery_postal_code')
                            ->label('Código Postal'),
                        TextEntry::make('city.name')
                            ->label('Ciudad / Localidad'),
                    ])
                    ->collapsible(),

                // Sección: PRODUCTOS COMPRADOS (estático)
                ComponentsSection::make('Productos en el Pedido')
                    ->icon('heroicon-o-shopping-bag')
                    ->schema([
                        ComponentsRepeatableEntry::make('items')
                            ->label(false)
                            ->schema([
                                TextEntry::make('product.title')
                                    ->label('Producto'),
                                ImageEntry::make('product.image_1') //ubicación de la imagen en la tabla
                                    ->label('Foto')
                                    ->disk('public') // disco por defecto
                                    ->imageSize(50) // Tamaño en píxeles (cuadrado de 50x50)
                                    ->circular(),
                                TextEntry::make('quantity')
                                    ->label('Cantidad'),
                                TextEntry::make('price')
                                    ->label('Precio Unitario')
                                    ->money('ARS'),
                            ]),
                    ]),

                // Sección: Estado y Pago
                ComponentsSection::make('Estado y Pago')
                    ->schema([
                        TextEntry::make('status')
                            ->label('Estado de la Orden')
                            ->badge()
                            ->formatStateUsing(fn($record) => $record->status_label) // Texto de estado al español, traído del modelo
                            ->color(fn(string $state): string => match ($state) { //color para cada uno de los estados
                                'pending' => 'warning',
                                'processing' => 'info',
                                'shipped' => 'primary',
                                'delivered' => 'success',
                                'cancelled' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('payment_method')
                            ->label('Método de Pago'),

                        TextEntry::make('total')
                            ->label('Total Cobrado')
                            ->money('ARS')
                            ->weight('bold')
                            ->size('lg'),
                    ]),

                // Sección: Metadatos
                ComponentsSection::make('Metadatos')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextEntry::make('fecha_argentina')
                            ->label('Fecha de Compra'),

                        TextEntry::make('user.name')
                            ->label('Usuario Registrado')
                            ->default('Usuario Eliminado (Historial Protegido)'),
                    ])
                    ->collapsible(),
            ]);
    }
}
