<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];

    //Un item de carrito solo se relaciona con un carrito
    public function cart(){
        return $this->belongsTo(Cart::class);
    }

    //Un item de carrito solo puede tener un producto
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
