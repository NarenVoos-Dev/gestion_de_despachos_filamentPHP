<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ciudades;

class CiudadSeeder extends Seeder
{

    public function run()
    {
        $ciudades = [
            'Barranquilla',
            'Bogotá',
            'Medellín',
            'Cali',
            'Cartagena',
            'Bucaramanga',
            'Santa Marta',
            'Villavicencio',
            'Manizales',
            'Cúcuta',
        ];

        foreach ($ciudades as $nombre) {
            Ciudades::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
