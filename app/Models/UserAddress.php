<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $table = 'user_addresses';
    
    protected $fillable = [
        'user_id',
        'city_id',
        'alias',
        'street',
        'postal_code',
        'is_default',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function province(){
        return $this->city->province;
    }
}
