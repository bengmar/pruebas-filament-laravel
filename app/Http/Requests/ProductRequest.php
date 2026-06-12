<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Sección 1: Información del Instrumento
            'brand_id'     => ['required', 'exists:brands,id'],
            'title'        => ['required', 'string', 'max:255'],
            'subtitle'     => ['required', 'string', 'max:255'],
            'category_id'  => ['required', 'exists:categories,id'],
            'description'  => ['required', 'string'],

            // Sección 2: Precios y Disponibilidad
            'stock'        => ['required', 'integer', 'min:0'],
            'price'        => ['required', 'numeric', 'min:0'],
            'on_sale'      => ['boolean'],
            // El descuento solo es requerido y numérico si 'on_sale' está marcado
            'discount'     => ['required_if:on_sale,true', 'nullable', 'numeric', 'between:0,100'],
            'sale_price'   => ['nullable', 'numeric'],
            'installments' => ['required', 'integer', 'min:1'],
            'installment_price' => ['nullable', 'numeric'],

            //Las imágenes las maneja filament
            // Sección 3: Especificaciones

            'specs'        => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'brand_id.required'    => 'Debes seleccionar una marca.',
            'title.required'       => 'El título del producto es obligatorio.',
            'price.required'       => 'El precio base es obligatorio.',
            'price.numeric'        => 'El precio debe ser un número válido.',
            'discount.required_if' => 'Si el producto está en oferta, debes indicar el porcentaje de descuento.',
            'discount.between'     => 'El descuento debe estar entre 0% y 100%.',
            'image_1.image'        => 'El archivo principal debe ser una imagen válida.',
        ];
    }
}
