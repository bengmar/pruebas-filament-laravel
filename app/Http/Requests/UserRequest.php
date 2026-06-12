<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        // Puedes cambiar esto según tu lógica de roles si es necesario
        return true;
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     */
    public function rules(): array
    {
        // Validamos si es una actualización para ignorar el ID actual en el campo único
        $userId = $this->route('user')?->id ?? $this->user;

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . $userId
            ],
            'password'   => $userId
                ? ['nullable', Password::defaults()] // Opcional al editar
                : ['required', Password::defaults()], // Obligatorio al crear
            'role_id'    => ['required', 'exists:roles,id'], // Validar que el rol exista
        ];
    }

    /**
     * Nombres de atributos personalizados para los errores.
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'nombre/s',
            'last_name'  => 'apellido/s',
            'email'      => 'correo electrónico',
            'password'   => 'contraseña',
            'role_id'    => 'rol',
        ];
    }
}
