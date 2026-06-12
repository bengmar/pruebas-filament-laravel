<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted()
    {
        // Alcance global para la tienda pública
        //en filament lo ignoro con withoutGlobalScopes() en el Resource
        static::addGlobalScope('active', function ($builder) {
            $builder->where('active', true) // 1. El producto debe estar activo
                ->whereHas('brand', function ($query) {
                    $query->where('active', true); // 2. La marca también debe estar activa
                });
        });

        // Lógica de las imágenes opcionales
        static::updating(function ($product) {
            $imagenesOpcionales = ['image_2', 'image_3'];
            foreach ($imagenesOpcionales as $campo) {
                //cpn isDirty Laravel solo trabaja si hubo un cambio real en la interfaz.
                //Si el campo pasó de tener una ruta a estar vacío (empty), va al disco public,
                //confirma que el archivo físico existe y lo borra. No deja basura.
                if ($product->isDirty($campo) && empty($product->$campo)) {
                    $oldImage = $product->getOriginal($campo);
                    if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
        });

        // AJUSTE CRÍTICO PARA SOFT DELETES:
        static::deleting(function ($product) {
            // El evento deleting se dispara tanto para Soft Delete como para Force Delete.
            // Con este IF, nos aseguramos de actuar SÓLO si es una eliminación permanente.
            if ($product->isForceDeleting()) {
                $todasLasImagenes = ['image_1', 'image_2', 'image_3'];

                foreach ($todasLasImagenes as $campo) {
                    if ($product->$campo && Storage::disk('public')->exists($product->$campo)) {
                        Storage::disk('public')->delete($product->$campo);
                    }
                }
            }
        });
    }

    protected $fillable = [
        'category_id',
        'brand_id',
        'title',
        'subtitle',
        'description',
        'stock',
        'price',
        'installments',
        'installment_price',
        'on_sale',
        'discount',
        'active',
        'specs',
        'image_1',
        'image_2',
        'image_3',
    ];
    protected $casts = [
        'specs' => 'array',  // para no hacer json_decode a mano
        'price' => 'decimal:2',
        'on_sale' => 'boolean',
        'active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Un producto solo se relaciona con un item del carrito
    public function cartItem()
    {
        return $this->belongsTo(CartItem::class);
    }

    //Un producto puede figurar en muchos ítems de pedidos históricos
    public function orderItem()
    {
        return $this->hasMany(OrderItem::class);
    }

    //Accesador para  $product->final_price
    public function getFinalPriceAttribute()
    {
        return self::calculateFinalPrice($this->price, $this->on_sale, $this->discount);
    }

    //Método para calcular precio final . Sirve para el modelo y para filament
    public static function calculateFinalPrice(float $price, bool $onSale, float $discount): float
    {
        if ($onSale && $discount > 0) {
            return $price - ($price * ($discount / 100));
        }
        return $price;
    }
}
