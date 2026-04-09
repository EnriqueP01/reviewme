<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MasterTestSeeder::class,
            // GroupCollaborationSeeder::class, // Integrated into MasterTestSeeder
        ]);
    }
}
