<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser; // La interfaz
use Filament\Panel;                         // La clase Panel que espera el método
use Illuminate\Foundation\Auth\User as Authenticatable;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

#[Fillable(['last_name', 'first_name', 'email', 'password', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public function canAccessPanel(Panel $panel): bool
    {
        // Solo el role_id 1 tiene permiso de entrar a /admin
        // Si role es null, devuelve false automáticamente sin lanzar errores
        return $this->role?->name === 'admin';
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    //un usuario tiene un perfil
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    // Un usuario puede tener muchas direcciones
    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id');
    }

    //Un usuario se relaciona con un carrito
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    //Un usuario puede tener muchas ordenes (falta el modelo)
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Devuelve solo la dirección marcada como predeterminada
    public function defaultAddress()
    {
        return $this->hasOne(UserAddress::class)->where('is_default', true);
    }

    //Necesario ya que filament usa 'name' y en la bd no uso ese
    public function getNameAttribute(): string
    {
        return "{$this->last_name} {$this->first_name}";
    }
}
