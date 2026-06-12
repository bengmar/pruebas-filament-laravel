<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = auth()->id();

        return [
            // Datos personales
            'first_name' => ['required', 'string', 'min:2', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'last_name'  => ['required', 'string', 'min:2', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'email'      => ['required', 'email:rfc', 'max:100', "unique:users,email,{$userId}"],
            'phone'      => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\+\-\(\)]+$/'],

            // Contraseña opcional
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)->letters()->numbers(),
            ],

            // Direcciones
            'addresses'                 => ['nullable', 'array', 'max:5'],
            'addresses.*.id'            => ['nullable', 'exists:user_addresses,id'],
            'addresses.*.alias'         => ['required_with:addresses.*', 'string', 'min:2', 'max:50'],
            'addresses.*.street'        => ['required_with:addresses.*', 'string', 'min:5', 'max:255'],
            'addresses.*.postal_code'   => ['required_with:addresses.*', 'regex:/^\d{4,8}$/'],
            'addresses.*.city_id'       => ['required_with:addresses.*', 'exists:cities,id'],
            'addresses.*.is_default'    => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required'           => 'El nombre es obligatorio.',
            'first_name.min'                => 'El nombre debe tener al menos 2 caracteres.',
            'first_name.regex'              => 'El nombre solo puede contener letras.',
            'last_name.required'            => 'El apellido es obligatorio.',
            'last_name.min'                 => 'El apellido debe tener al menos 2 caracteres.',
            'last_name.regex'               => 'El apellido solo puede contener letras.',
            'email.required'                => 'El email es obligatorio.',
            'email.email'                   => 'Ingresá un email válido.',
            'email.unique'                  => 'Este email ya está en uso por otra cuenta.',
            'phone.regex'                   => 'El teléfono solo puede contener números, espacios y los caracteres +, -, (, ).',
            'password.confirmed'            => 'Las contraseñas no coinciden.',
            'password.min'                  => 'La contraseña debe tener al menos 8 caracteres.',
            'addresses.max'                 => 'Podés registrar un máximo de 5 direcciones.',
            'addresses.*.alias.required_with'       => 'El alias de la dirección es obligatorio.',
            'addresses.*.alias.min'                 => 'El alias debe tener al menos 2 caracteres.',
            'addresses.*.street.required_with'      => 'La calle es obligatoria.',
            'addresses.*.street.min'                => 'Ingresá una dirección más completa.',
            'addresses.*.postal_code.required_with' => 'El código postal es obligatorio.',
            'addresses.*.postal_code.regex'         => 'El código postal solo puede contener números (4 a 8 dígitos).',
            'addresses.*.city_id.required_with'     => 'La ciudad es obligatoria.',
            'addresses.*.city_id.exists'            => 'La ciudad seleccionada no es válida.',
        ];
    }
}
