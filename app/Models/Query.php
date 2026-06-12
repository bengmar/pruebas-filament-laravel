<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Query extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
    ];

    /**
     * Accesor moderno para el Asunto (Compatible con Filament)
     */
    protected function asuntoTexto(): Attribute
    {
        return Attribute::get(function () {
            $tipos = [
                1 => 'Formas de pago',
                2 => 'Modos/costos de envío',
                3 => 'Devolución',
                4 => 'Cuenta',
                5 => 'Otros',
            ];
            return $tipos[$this->subject] ?? 'No especificado';
        });
    }

    protected $casts = [
        'subject' => 'integer'
    ];

    /**
     * Accesor para la Fecha: Pasa de UTC a Horario Argentina (ART).
     */
    public function getFechaArgentinaAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])
            ->timezone('America/Argentina/Buenos_Aires')
            ->format('d/m/Y H:i');
    }
}
