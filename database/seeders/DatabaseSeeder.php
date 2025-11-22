<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            KecamatanSeeder::class,
            SektorSeeder::class,
            PendidikanSeeder::class,
            UsiaSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
