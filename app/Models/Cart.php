<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    //Un carrito se relaciona con un usuario
    public function user(){
        return $this->belongsTo(User::class);
    }

    //Un carrito puede tener muchos items
    public function items(){
        return $this->hasMany(CartItem::class);
    }
}
