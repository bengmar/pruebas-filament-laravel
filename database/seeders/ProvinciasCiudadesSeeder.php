<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
 
class ProvinciasCiudadesSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Buenos Aires' => [
                'La Plata', 'Mar del Plata', 'Bahía Blanca', 'Quilmes', 'Lanús',
                'Lomas de Zamora', 'Almirante Brown', 'Merlo', 'Moreno', 'Tigre',
                'San Isidro', 'Tres de Febrero', 'Florencio Varela', 'Berazategui',
                'Esteban Echeverría', 'Malvinas Argentinas', 'José C. Paz', 'San Miguel',
                'Hurlingham', 'Ituzaingó', 'Morón', 'La Matanza', 'Avellaneda',
                'Vicente López', 'San Martín', 'Tandil', 'Pergamino', 'Junín',
                'Olavarría', 'Necochea', 'Zárate', 'Campana', 'San Nicolás',
                'Azul', 'Luján', 'Mercedes', 'Pilar', 'Escobar',
            ],
            'Ciudad Autónoma de Buenos Aires' => [
                'Palermo', 'Belgrano', 'Caballito', 'Flores', 'Villa Urquiza',
                'Núñez', 'Recoleta', 'San Telmo', 'Balvanera', 'Almagro',
                'Villa Crespo', 'Mataderos', 'Liniers', 'Boedo', 'Parque Patricios',
            ],
            'Catamarca' => [
                'San Fernando del Valle de Catamarca', 'San Isidro', 'Valle Viejo',
                'Tinogasta', 'Santa María', 'Belén', 'Andalgalá',
            ],
            'Chaco' => [
                'Resistencia', 'Presidencia Roque Sáenz Peña', 'Villa Ángela',
                'Charata', 'Quitilipi', 'Barranqueras', 'Fontana',
            ],
            'Chubut' => [
                'Rawson', 'Comodoro Rivadavia', 'Trelew', 'Puerto Madryn',
                'Esquel', 'Rada Tilly', 'Río Gallegos',
            ],
            'Córdoba' => [
                'Córdoba', 'Villa María', 'Río Cuarto', 'San Francisco',
                'Alta Gracia', 'Villa Carlos Paz', 'Cosquín', 'Jesús María',
                'Bell Ville', 'Laboulaye', 'Marcos Juárez', 'La Falda',
                'Villa del Rosario', 'Río Tercero', 'Arroyito',
            ],
            'Corrientes' => [
                'Corrientes', 'Goya', 'Curuzú Cuatiá', 'Mercedes',
                'Paso de los Libres', 'Esquina', 'Bella Vista', 'Saladas',
            ],
            'Entre Ríos' => [
                'Paraná', 'Concordia', 'Gualeguaychú', 'Concepción del Uruguay',
                'Colón', 'Villaguay', 'Gualeguay', 'Federal',
            ],
            'Formosa' => [
                'Formosa', 'Clorinda', 'Pirané', 'El Colorado', 'Ingeniero Juárez',
            ],
            'Jujuy' => [
                'San Salvador de Jujuy', 'Palpalá', 'San Pedro de Jujuy',
                'Libertador General San Martín', 'Humahuaca', 'Tilcara',
            ],
            'La Pampa' => [
                'Santa Rosa', 'General Pico', 'Realicó', 'General Acha',
                'Toay', 'Eduardo Castex',
            ],
            'La Rioja' => [
                'La Rioja', 'Chilecito', 'Aimogasta', 'Chamical',
                'Chepes', 'Villa Unión',
            ],
            'Mendoza' => [
                'Mendoza', 'San Rafael', 'Godoy Cruz', 'Guaymallén',
                'Las Heras', 'Luján de Cuyo', 'Maipú', 'Rivadavia',
                'Junín', 'General Alvear', 'Tupungato',
            ],
            'Misiones' => [
                'Posadas', 'Oberá', 'Eldorado', 'Puerto Iguazú',
                'Apóstoles', 'Leandro N. Alem', 'Jardín América',
            ],
            'Neuquén' => [
                'Neuquén', 'San Martín de los Andes', 'Zapala',
                'Cutral Có', 'Plaza Huincul', 'Junín de los Andes',
            ],
            'Río Negro' => [
                'Viedma', 'San Carlos de Bariloche', 'Cipolletti',
                'General Roca', 'Allen', 'Villa Regina', 'El Bolsón',
            ],
            'Salta' => [
                'Salta', 'Orán', 'Tartagal', 'Metán',
                'Cafayate', 'Rosario de la Frontera', 'General Güemes',
            ],
            'San Juan' => [
                'San Juan', 'Rawson', 'Rivadavia', 'Chimbas',
                'Santa Lucía', 'Pocito', 'Caucete',
            ],
            'San Luis' => [
                'San Luis', 'Villa Mercedes', 'Merlo', 'Quines',
                'Justo Daract', 'Arizona',
            ],
            'Santa Cruz' => [
                'Río Gallegos', 'Caleta Olivia', 'Puerto Deseado',
                'Las Heras', 'Pico Truncado', 'El Calafate',
            ],
            'Santa Fe' => [
                'Santa Fe', 'Rosario', 'Rafaela', 'Venado Tuerto',
                'Villa Constitución', 'Reconquista', 'Santo Tomé',
                'Esperanza', 'Casilda', 'Cañada de Gómez', 'Pérez',
            ],
            'Santiago del Estero' => [
                'Santiago del Estero', 'La Banda', 'Termas de Río Hondo',
                'Frías', 'Añatuya', 'Loreto',
            ],
            'Tierra del Fuego' => [
                'Ushuaia', 'Río Grande', 'Tolhuin',
            ],
            'Tucumán' => [
                'San Miguel de Tucumán', 'Tafí Viejo', 'Banda del Río Salí',
                'Yerba Buena', 'Concepción', 'Aguilares', 'Famaillá',
                'Monteros', 'Simoca',
            ],
        ];
 
        foreach ($data as $nombreProvincia => $ciudades) {
            $provincia = DB::table('provinces')->insertGetId([
                'name'     => $nombreProvincia,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
 
            $rows = array_map(fn($ciudad) => [
                'province_id' => $provincia,
                'name'       => $ciudad,
                'created_at'   => now(),
                'updated_at'   => now(),
            ], $ciudades);
 
            DB::table('cities')->insert($rows);
        }
 
        $this->command->info('Provincias y ciudades cargadas correctamente.');
    }
}
 