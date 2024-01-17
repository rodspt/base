<?php

namespace Database\Seeders;

class DatabaseSeeder extends PersonalSeeder
{
    public function run(): void
    {
      $this->callUnique(PerfilSeeder::class);
      $this->callUnique(UserSeeder::class);
    }

}
