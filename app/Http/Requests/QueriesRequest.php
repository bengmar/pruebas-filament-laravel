<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class QueriesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'name' => ['required','string','max:255'],
            'email' => ['required', 'email'],
            'subject' => ['required'],
            'message' =>['required','string','min:10','max:500']
        ];
    }
    #[Override]
    public function messages(): array
    {
        return [
            'name.required' => 'Debe ingresar su nombre.',
            'name.max' => 'Se admiten 255 caracteres como máximo.',
            'email.email' => 'Se requiere un email válido.',
            'email.required' => 'Debe ingresar un e-mail de contacto.',
            'subject.required'=> 'Debe seleccionar un asunto.',
            'message.min' => 'El mensaje es muy breve.',
            'message.max' => 'Se admiten 500 caracteres como máximo.',
            'message.required' => 'Debe escribir un mensaje.'
        ];
    }
}
