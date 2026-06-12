<?php

namespace App\Filament\Resources\Orders\Schemas;

// IMPORTACIONES CORRECTAS PARA FORMULARIOS EN FILAMENT v5
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;
use App\Models\Order;
use Filament\Schemas\Components\Section as ComponentsSection;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Datos del Cliente
                ComponentsSection::make('Información del Cliente')
                    ->description('Datos proporcionados por el comprador al momento del checkout.')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextInput::make('customer_name')
                            ->label('Nombre')
                            ->disabled(),
                        TextInput::make('customer_lastname')
                            ->label('Apellido')
                            ->disabled(),
                        TextInput::make('customer_email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->disabled(),
                    ])
                    ->collapsible(),

                // Dirección de Envío
                ComponentsSection::make('Dirección de Entrega')
                    ->description('Destino donde se debe despachar el paquete.')
                    ->icon('heroicon-o-truck')
                    ->schema([
                        TextInput::make('delivery_street')
                            ->label('Calle y Número')
                            ->disabled(),
                        TextInput::make('delivery_postal_code')
                            ->label('Código Postal')
                            ->disabled(),
                        Select::make('delivery_city_id')
                            ->label('Ciudad / Localidad')
                            ->relationship('city', 'name')
                            ->disabled(),
                    ])
                    ->collapsible(),

                // ÍTEMS COMPRADOS
                ComponentsSection::make('Productos en el Pedido')
                    ->icon('heroicon-o-shopping-bag')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->label(false)
                            ->schema([
                                Select::make('product_id')
                                    ->label('Producto')
                                    // Con esto, Filament puede cargar el título del producto aunque tenga Soft Delete
                                    //Al ser un select, necesita este ajuste. En cambio en la tabla y la infolist
                                    //solo mira que el método product() de OrderItem incluya withTrashed()
                                    ->relationship(
                                        name: 'product',
                                        titleAttribute: 'title',
                                        modifyQueryUsing: fn($query) => $query
                                            ->withoutGlobalScopes()  // remueve TODOS los global scopes
                                            ->withTrashed()
                                    )
                                    ->disabled(),
                                TextInput::make('quantity')
                                    ->label('Cantidad')
                                    ->numeric()
                                    ->disabled(),
                                TextInput::make('price')
                                    ->label('Precio Unitario')
                                    ->numeric()
                                    ->prefix('$')
                                    ->disabled(),
                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false),
                    ]),

                // Estado y Pago
                ComponentsSection::make('Estado y Pago')
                    ->schema([
                        Select::make('status')
                            ->label('Estado de la Orden')
                            ->required()
                            ->options([
                                'pending' => 'Pendiente de Pago',
                                'processing' => 'En Proceso / Armando Pedido',
                                'shipped' => 'Despachado / Enviado',
                                'delivered' => 'Entregado',
                                'cancelled' => 'Cancelado',
                            ])
                            ->selectablePlaceholder(false),

                        TextInput::make('payment_method')
                            ->label('Método de Pago')
                            ->disabled(),

                        TextInput::make('total')
                            ->label('Total Cobrado')
                            ->prefix('$')
                            ->formatStateUsing(fn($state) => number_format((float)$state, 2, ',', '.'))
                            ->readOnly()
                            ->dehydrated(false)
                            ->extraInputAttributes(['class' => 'font-bold text-lg text-primary-600']),
                    ]),

                // Metadatos
                // Sección: Metadatos
                ComponentsSection::make('Metadatos')
                    ->schema([
                        TextInput::make('created_at')
                            ->label('Fecha de Compra')
                            ->readOnly()
                            ->dehydrated(false)
                            ->afterStateHydrated(function (TextInput $component, $state) {
                                if ($state) {
                                    $component->state(\Carbon\Carbon::parse($state)
                                        ->setTimezone('America/Argentina/Buenos_Aires')
                                        ->format('d/m/Y H:i'));
                                }
                            }),

                        //  FORMULARIO BLINDADO PARA USUARIOS ELIMINADOS
                        TextInput::make('user_fullname') //Es solo un campo representativo.
                            ->label('Usuario Registrado')
                            ->disabled()
                            ->formatStateUsing(function (Order $record) {
                                // Si el usuario se borró definitivamente (user_id quedó en null)
                                if (! $record->user) {
                                    return 'Usuario Eliminado (Historial Protegido)';
                                }

                                // Si el usuario sigue activo en el sistema
                                return $record->user->name;
                            }),
                    ])
                    ->icon('heroicon-o-information-circle')
                    ->collapsible(),
            ]);
    }
}
