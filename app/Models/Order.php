<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'payment_method',
        'status',
        'customer_name',
        'customer_lastname',
        'customer_email',
        'delivery_street',
        'delivery_postal_code',
        'delivery_city_id',
    ];

    // Una orden tiene muchos ítems comprados
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Una orden pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Una orden se envía a una ciudad específica
    public function city()
    {
        return $this->belongsTo(City::class, 'delivery_city_id');
    }
    /**
     * Accesor para la Fecha: Pasa de UTC a Horario Argentina (ART).
     */
    public function getFechaArgentinaAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])
            ->timezone('America/Argentina/Buenos_Aires')
            ->format('d/m/Y H:i');
    }

    /**
     * Obtiene el nombre del estado en español de forma dinámica.
     * Es otra forma de accesor
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $statuses = [
                    'pending'    => 'Pendiente de Pago',
                    'processing' => 'En Proceso',
                    'shipped'    => 'Despachado',
                    'delivered'  => 'Entregado',
                    'cancelled'  => 'Cancelado',
                ];

                // Si el estado en la BD no existe en el array, por seguridad devuelve 'Desconocido'
                return $statuses[$attributes['status']] ?? 'Desconocido';
            },
        );
    }
    
    protected function paymentMethodLabel(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $methods = [
                    'credit'   => 'Tarjeta de Crédito',
                    'transfer_bank' => 'Transferencia Bancaria',
                    'transfer_mp' => 'Mercado Pago',
                ];

                // Si el método de pago en la BD no existe en el array, por seguridad devuelve 'Desconocido'
                return $methods[$attributes['payment_method']] ?? 'Desconocido';
            },
        );
    }
}
