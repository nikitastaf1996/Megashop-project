<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Image::class;
    public function definition(): array
    {   
        $images = DB::table('placeholders')->where('type','image')->pluck('value');
        return [
            "image_path" => fake()->randomElement($images)
        ];
    }
}
