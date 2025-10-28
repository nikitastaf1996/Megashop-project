<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $items = DB::table('items')->pluck('id');
        $users = DB::table('users')->pluck('id');

        return [
            'text' => fake()->paragraphs(rand(2, 4), true),
            'grade' => rand(1, 5),
            'user_id' => fake()->randomElement($users),
            'item_id' => fake()->randomElement($items)
        ];
    }
}
