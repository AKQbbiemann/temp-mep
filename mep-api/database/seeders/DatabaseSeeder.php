<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ClusterSeeder::class,
            CompetencesSeeder::class,
            RequirementsSeeder::class,
        ]);
    }
}
