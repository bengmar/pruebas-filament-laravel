<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // 1. Crear los Roles
        $adminRole = Role::create(['name' => 'admin']); // ID 1
        Role::create(['name' => 'client']);
        User::create([
            'first_name' => 'Admin',
            'last_name'  => 'Soundwave',
            'email'      => 'admin@soundwave.com',
            'password'   => Hash::make('admin123'), // La contraseña que quieras
            'role_id'    => $adminRole->id,         // O simplemente 1
        ]);

        Category::create(['name' => 'Otros']); //Creación de la categoría base.
        
        $this->call([
            ProductSeeder::class,
            UpdateCategoryDisplayTitlesSeeder::class,
            MigrateImagesSeeder::class,
            ProvinciasCiudadesSeeder::class,
        ]);
    }
}
