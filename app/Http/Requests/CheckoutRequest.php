<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
         $rules = [
            'customer_name'     => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\pL\s\-]+$/u'],
            'customer_lastname'  => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\pL\s\-]+$/u'],
            'customer_email'    => ['required', 'email:rfc,dns', 'max:100'],
            'paymentMethod'     => ['required', 'in:credit,transfer_bank,transfer_mp'],
        ];

        // Si elige dirección guardada
        if ($this->input('user_address_id') !== 'nueva_direccion') {
            $rules['user_address_id'] = [
                'required',
                'exists:user_addresses,id,user_id,' . auth()->id(), // solo sus propias direcciones
            ];
        } else {
            // Si ingresa una nueva dirección
            $rules['delivery_street']      = ['required', 'string', 'min:5', 'max:150'];
            $rules['delivery_postal_code'] = ['required', 'string', 'regex:/^\d{4,8}$/'];
            $rules['delivery_alias']       = ['required', 'string', 'min:2', 'max:50'];
            $rules['delivery_city_id']     = ['required', 'exists:cities,id'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'customer_name.required'          => 'El nombre es obligatorio.',
            'customer_name.min'               => 'El nombre debe tener al menos 2 caracteres.',
            'customer_name.regex'             => 'El nombre solo puede contener letras.',
            'customer_lastname.required'      => 'El apellido es obligatorio.',
            'customer_lastname.min'           => 'El apellido debe tener al menos 2 caracteres.',
            'customer_lastname.regex'         => 'El apellido solo puede contener letras.',
            'customer_email.required'         => 'El email es obligatorio.',
            'customer_email.email'            => 'Ingresá un email válido.',
            'paymentMethod.required'          => 'Seleccioná un método de pago.',
            'paymentMethod.in'               => 'El método de pago seleccionado no es válido.',
            'user_address_id.required'        => 'Seleccioná una dirección de entrega.',
            'user_address_id.exists'          => 'La dirección seleccionada no es válida.',
            'delivery_street.required'        => 'La calle es obligatoria.',
            'delivery_street.min'             => 'Ingresá una dirección más completa.',
            'delivery_postal_code.required'   => 'El código postal es obligatorio.',
            'delivery_postal_code.regex'      => 'El código postal solo puede contener números.',
            'delivery_alias.required'         => 'El alias de la dirección es obligatorio.',
            'delivery_city_id.required'       => 'Seleccioná una ciudad.',
            'delivery_city_id.exists'         => 'La ciudad seleccionada no es válida.',
        ];
    }
}
