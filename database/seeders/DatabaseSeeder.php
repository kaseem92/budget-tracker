<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Backend seed data will be added after the UI is finalized.
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
