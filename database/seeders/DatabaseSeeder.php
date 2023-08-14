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
            'name' => 'teste',
            'email' => 'teste@mail.com',
            'password' => bcrypt('teste')
        ]);

        // Test user 2
        User::factory()->create([
            'name' => 'teste2',
            'email' => 'teste2@mail.com',
            'password' => bcrypt('teste2')
        ]);
    }
}
