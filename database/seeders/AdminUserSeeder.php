<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear el rol si no existe
        $role = Role::firstOrCreate(['name' => 'administrador']);

        // Crear usuario
        $user = User::firstOrCreate(
            ['email' => 'admin@nutrimedical.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'), // Puedes cambiar la contraseña
            ]
        );

        // Asignar rol
        if (!$user->hasRole('administrador')) {
            $user->assignRole($role);
        }

        $this->command->info('Usuario administrador creado: admin@example.com / password');
    }
}
