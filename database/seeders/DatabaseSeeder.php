<?php

namespace Database\Seeders;

class DatabaseSeeder extends PersonalSeeder
{
    public function run(): void
    {
      $this->callUnique(UserSeeder::class);
    }

}
