<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seeder admin (dipakai saat install awal / fresh database)
        $this->call(AdminSeeder::class);
    }
}
