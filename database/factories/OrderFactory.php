<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Order::class;

    public function definition(): array
    {

        return [
            "datetime" => fake()->dateTimeBetween("-6 months"),
            'price' => fake()->randomFloat(2, 10, 10000),
            'item_id' => 1,
            'user_id' => 1
        ];
    }
}
