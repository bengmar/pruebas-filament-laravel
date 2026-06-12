<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateCategoryDisplayTitlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nombresCategorias = [
            1 => 'Otros',  //Categoría base
            2 => 'Instrumentos Musicales',
            3 => 'Equipos de Audio y Sonido',
            4 => 'Trípodes y Soportes',
            5 => 'Accesorios',
            6 => 'Iluminación y Estudio',
        ];

        foreach ($nombresCategorias as $id => $displayTitle) {
            // Buscamos la categoría por ID y le actualizamos el campo
            Category::query()->where('id', $id)->update([
                'display_title' => $displayTitle
            ]);
        }
    }
}

