<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeederTest extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ClientSeeder::class
        ]);
        
    }
}
