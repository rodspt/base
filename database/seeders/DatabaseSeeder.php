<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Test user 1
        User::factory()->create([
            'cpf' => '03106929111',
            'name' => 'teste',
            'email' => 'teste@mail.com',
            'password' => bcrypt('teste123456')
        ]);

    }
}
