<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cambiar a true para permitir el uso
    }

    public function rules(): array
    {
        return [
            // Requerido, mínimo 3 caracteres, y nos aseguramos de que no sean solo espacios
            'query' => 'required|string|min:3',
        ];
    }

    public function messages(): array
    {
        return [
            'query.required' => 'Por favor, ingresa un término de búsqueda.',
            'query.min' => 'Por favor, ingresa al menos 3 caracteres.',
        ];
    }

    /**
     * Opcional: Limpiar el input antes de validar (reemplaza el trim() del controlador)
     */
    protected function prepareForValidation()
    {
        if ($this->has('query')) {
            $this->merge([
                'query' => trim($this->input('query')),
            ]);
        }
    }

    /**
     * Personalizar la respuesta cuando falla la validación
     */
    protected function failedValidation(Validator $validator)
    {
        // Para la petición AJAX, se devuelve vacío
        if ($this->ajax()) {
            throw new HttpResponseException(response(''));
        }

        // Para petición normal, redirige atrás con los errores.
        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator) //cargar el error para @error('query')
                ->withInput() //mantener lo que se escribió
        );
    }
}
