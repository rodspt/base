<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
      $this->callUnique(UserSeeder::class);
    }

    private function callUnique($class)
    {
        $nameClass = class_basename($class);
        if (!$this->seederAlreadyExecuted($nameClass)) {
            try{
                $this->callOnce($class);
                $this->markSeederAsExecuted($nameClass);
            } catch (\Exception) {
                throw new Exception("Ocorreu um erro na execução do seed: $nameClass");
            }
        }
    }

    private function seederAlreadyExecuted($seederName)
    {
        return \DB::table('seed')->where('name', $seederName)->exists();
    }

    private function markSeederAsExecuted($seederName)
    {
        \DB::table('seed')->insert([
            'name' => $seederName,
            'created_at' => now(),
        ]);
    }
}
