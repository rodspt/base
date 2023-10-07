<?php

namespace Database\Seeders;

use App\Models\Teste;
use App\Models\User;
use Database\Factories\TestFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Test user 1

        factory(TestFactory::class, 50)->create();
    }
}
