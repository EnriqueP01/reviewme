<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\Review;
use App\Models\Snippet;
use App\Models\User;
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
        ]);
    }
}
