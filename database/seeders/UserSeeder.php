<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'cpf' => '03106929111',
            'name' => 'teste',
            'email' => 'teste@mail.com',
            'password' => bcrypt('teste123456')
        ]);
    }
}
