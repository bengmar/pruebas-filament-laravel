<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MigrateImagesSeeder extends Seeder
{
    public function run(): void
    {
        $sourceDir = public_path('images');
        $products  = Product::withoutGlobalScopes()->get();

        foreach ($products as $product) {
            foreach (['image_1', 'image_2', 'image_3'] as $field) {
                $oldPath = $product->$field; // ej: "images/guitarra-prs.webp"

                if (!$oldPath) continue;

                $filename    = basename($oldPath);           // "guitarra-prs.webp"
                $newPath     = 'products/images/' . $filename;      // path relativo en storage
                $sourceFile  = $sourceDir . '/' . $filename; // ruta absoluta origen

                if (File::exists($sourceFile)) {
                    // Copia el archivo a storage/app/public/products/
                    Storage::disk('public')->put(
                        $newPath,
                        File::get($sourceFile)
                    );

                    // Actualiza la BD con la nueva ruta relativa
                    $product->$field = $newPath;
                }
            }

            $product->save();
        }

        $this->command->info('Imágenes migradas correctamente.');
    }
}
