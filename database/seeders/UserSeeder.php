<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

use Faker\Provider\pt_BR\Person;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cpf =  '00000000191';
        User::factory()->create([
            'cpf' => $cpf,
            'nome' => 'teste',
            'email' => 'teste@mail.com',
            'perfil_id' => 1,
            'password' => bcrypt('teste123456'),
            'cpf_aprovacao' => $cpf,
            'aprovacao_at' => Carbon::now()
        ]);
    }
}
