<?php

namespace Database\Seeders;

use App\Models\Perfil;
use Illuminate\Database\Seeder;

class PerfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Perfil::factory()->create([
            'name' => 'Super usuário',
            'description' => 'Perfil de desenvolvedor',
            'hide' => true
          ]);
        Perfil::factory()->create([
            'name' => 'Administrador',
            'description' => 'Perfil de Administrador'
        ]);
        Perfil::factory()->create([
            'name' => 'Consulta',
            'description' => 'Perfil de Consulta'
        ]);
    }
}
