<?php

namespace Database\Seeders;

use App\Models\Category as Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory()->count(10)->create()->each(
            function($item){
                Category::factory()->count(10)->create([
                    'parent_id' => $item->id
                ]);
            }
        );
    }
}
