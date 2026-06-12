<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Http\Requests\ProductRequest;
use App\Models\Brand;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProductForm
{

    public static function updatePrices(Get $get, Set $set): void
    {
        $basePrice = (float) $get('price');
        $onSale = (bool) $get('on_sale');
        $discount = (float) $get('discount');
        $installments = (int) $get('installments') ?: 1; //cuotas. Si no se especifica: 1.

        //Utilización del método del modelo para la obtención del precio
        $finalPrice = Product::calculateFinalPrice($basePrice, $onSale, $discount);

        // Actualizamos el campo visual del precio de oferta
        $set('sale_price', round($finalPrice, 2));

        // Actualizamos el valor de la cuota en base al descuento.
        $installmentValue = $finalPrice / $installments;
        $set('installment_price', round($installmentValue, 2));
    }
    public static function configure(Schema $schema): Schema
    {
        //Instanciación del request para traer las reglas y mensajes
        $request = new ProductRequest();
        $rules = $request->rules();
        $messages = $request->messages();

        return $schema
            ->schema([
                //Notificar si el producto está invisible al público debido a la marca.
                TextInput::make('brand_status_helper')
                    ->label('Alerta de Visibilidad')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(fn() => '⚠ OCULTO — La marca asociada está desactivada comercialmente.')
                    ->extraInputAttributes([
                        'class' => 'text-amber-700 bg-amber-50 dark:bg-amber-950/20 dark:text-amber-400 border-amber-300 font-bold'
                    ])
                    ->columnSpanFull()
                    ->hidden(fn($record) => !($record?->brand && !$record->brand->active)),
                // SECCIÓN 1 : Detalles del producto
                Section::make('Información del Instrumento')
                    ->description('Detalles principales del producto')
                    ->schema([
                        Select::make('brand_id')
                            ->label('Marca')
                            ->relationship(
                                name: 'brand',
                                titleAttribute: 'name',
                                modifyQueryUsing: function (Builder $query, ?Model $record) {
                                    // Si el producto existe y tiene marca (estamos editando)
                                    if ($record && $record->brand_id) {
                                        return $query->where(function ($q) use ($record) {
                                            $q->where('active', 1)
                                                ->orWhere('id', $record->brand_id); // Rescatamos la marca actual aunque active = 0
                                        });
                                    }

                                    // Si estamos creando un producto nuevo, solo marcas activas
                                    return $query->where('active', 1);
                                }
                            )
                            ->searchable()
                            ->preload()
                            ->required(), // Mantiene el asterisco y valida que no vaya vacío
                        TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->rules($rules['title'])
                            ->validationMessages($messages),
                        TextInput::make('subtitle') // Modelo del producto
                            ->label('Modelo') //(sin required en filament para probar validación laravel) - no se dibuja el *
                            ->unique()
                            ->rules($rules['subtitle']),

                        Select::make('category_id')
                            ->label('Categoría')
                            ->relationship(
                                name: 'category',
                                titleAttribute: 'name',
                                //Función para modificar la consulta
                                modifyQueryUsing: function (Builder $query, $record) { //parametros: la consulta y el modelo actual (producto)
                                    // Caso 1: Editando un producto existente
                                    if ($record && $record->category_id) { //si el modelo del producto actual ya está en la bbdd y tiene una categoria
                                        return $query
                                            ->withTrashed() //Trae todas las categorías, incluidas las que tienen soft deletes
                                            ->where(function (Builder $q) use ($record) {
                                                $q->whereNull('deleted_at')               // son las activas
                                                    ->orWhere('id', $record->category_id);  // o  de las quitadas, la categoría actual (aunque esté eliminada)
                                            });
                                    } // basicamente traemos todas, y despues dejamos solo las activas y la que pertenece al modelo actual (si está desactivada).

                                    // Caso 2: Creando un producto nuevo
                                    return $query->whereNull('deleted_at'); //para un producto nuevo, solo las activas
                                }
                            )
                            ->label('Categoría')
                            ->required()
                            ->rules($rules['category_id']),

                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(5)
                            ->rules($rules['description']),
                    ])->columns(2),

                // SECCIÓN 2: Precio, financiación y disponibilidad
                Section::make('Precios,Financiación Y Disponibilidad')
                    ->schema([
                        TextInput::make('stock')
                            ->label('Stock Del Producto')
                            ->numeric()
                            ->rules($rules['stock']),

                        TextInput::make('price')
                            ->label('Precio Base')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Get $get, Set $set) => self::updatePrices($get, $set))
                            ->rules($rules['price'])
                            ->validationMessages($messages),

                        Toggle::make('on_sale')
                            ->label('¿En oferta?')
                            ->live()
                            ->afterStateUpdated(fn(Get $get, Set $set) => self::updatePrices($get, $set))
                            ->rules($rules['on_sale']),

                        TextInput::make('discount')
                            ->label('% Descuento')
                            ->numeric()
                            ->suffix('%')
                            ->visible(fn(Get $get) => $get('on_sale'))
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Get $get, Set $set) => self::updatePrices($get, $set))
                            ->rules($rules['discount'])
                            ->validationMessages($messages),

                        TextInput::make('sale_price')
                            ->label('Precio Oferta')
                            ->placeholder(function (Get $get) {
                                $price = (float) $get('price');
                                $discount = (float) $get('discount');
                                if ($get('on_sale') && $discount > 0) {
                                    return '$' . number_format($price * (1 - ($discount / 100)), 2);
                                }
                                return 'N/A';
                            })
                            ->readOnly()
                            ->rules($rules['sale_price']),

                        TextInput::make('installments')
                            ->label('Cantidad de Cuotas')
                            ->numeric()
                            ->default(1)
                            ->live()
                            ->afterStateUpdated(fn(Get $get, Set $set) => self::updatePrices($get, $set))
                            ->rules($rules['installments']),

                        TextInput::make('installment_price')
                            ->label('Valor de cada Cuota')
                            ->numeric()
                            ->prefix('$')
                            ->readOnly()
                            ->extraAttributes(['class' => 'bg-gray-50'])
                            ->rules($rules['installment_price']),
                    ])->columns(3),

                // SECCIÓN 3: MULTIMEDIA
                Section::make('Imágenes del Producto')
                    ->schema([
                        //IMAGEN 1: Obligatoria
                        FileUpload::make('image_1')
                            ->label('Imagen Principal')
                            ->image()
                            ->required()
                            ->maxSize(2048)
                            ->disk('public')
                            ->directory('products/images')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->imageEditor()
                            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file, $record) {
                                if ($record && $record->image_1) {
                                    Storage::disk('public')->delete($record->image_1);
                                }
                                return $file->storeAs('products/images', $file->getClientOriginalName(), 'public');
                            })
                            ->deleteUploadedFileUsing(function ($state) {
                                /* Si el administrador presiona la "X" en la interfaz pero luego se arrepiente
                                y cancela o cierra el navegador, el archivo físico sigue a salvo en el servidor.
                                Solo se destruirá la imagen vieja si se arrastra una foto nueva válida
                                y se da a "Guardar" (saveUploadedFileUsing), o si borra el producto permanentemente
                                (desde la lógica forceDeleting -si se aplica- del modelo).*/
                                return null;
                            }),
                        //IMAGEN 2: Opcional
                        FileUpload::make('image_2')
                            ->label('Imagen Extra 1')
                            ->image()
                            ->maxSize(2048)
                            ->disk('public')
                            ->directory('products/images')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->imageEditor()
                            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file, $record) {
                                if ($record && $record->image_2) {
                                    Storage::disk('public')->delete($record->image_2);
                                }
                                return $file->storeAs('products/images', $file->getClientOriginalName(), 'public');
                            }),
                        //IMAGEN 3: Opcional
                        FileUpload::make('image_3')
                            ->label('Imagen Extra 2')
                            ->image()
                            ->maxSize(2048)
                            ->disk('public')
                            ->directory('products/images')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->imageEditor()
                            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file, $record) {
                                if ($record && $record->image_3) {
                                    Storage::disk('public')->delete($record->image_3);
                                }
                                return $file->storeAs('products/images', $file->getClientOriginalName(), 'public');
                            }),
                    ])->columns(3),

                // SECCIÓN 4: Especificaciones técnicas (JSON)
                Section::make('Especificaciones')
                    ->schema([
                        // Como en el modelo está como 'specs' => 'array'
                        KeyValue::make('specs')
                            ->label('Características Técnicas')
                            ->keyLabel('Propiedad (ej: Trastes)')
                            ->valueLabel('Valor (ej: 22)')
                            ->columnSpanFull()
                            ->rules($rules['specs']),
                    ]),
            ]);
    }
}
