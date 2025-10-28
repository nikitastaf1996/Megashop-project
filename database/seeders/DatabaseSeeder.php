<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PlaceholderSeeder::class,
            CategorySeeder::class,
            ItemSeeder::class,
            UserSeeder::class,
            ReviewSeeder::class,
            OrderSeeder::class
        ]);
    }
}
