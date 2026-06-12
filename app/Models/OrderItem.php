<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    // El ítem pertenece a una orden madre
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // El ítem está asociado a un producto del catálogo
    public function product()
    {
        // Usamos withTrashed() por si un producto se elimina del catálogo (Soft Delete)
        // para que el historial de compras del usuario no rompa la aplicación
        return $this->belongsTo(Product::class)
            ->withoutGlobalScopes(['active']) // Ignora si la marca o el producto están apagados
            ->withTrashed();
    }
}
