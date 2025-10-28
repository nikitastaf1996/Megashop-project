<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Image;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
     private function generatePrices($itemId)
     {
         $sixMonthsAgo = now()->subMonths(6);
         $oneMonthInTheFuture = now()->addMonth();
         $currentDay = $sixMonthsAgo;
         $data = [];
         while ($currentDay->isBefore($oneMonthInTheFuture)) {
             $priceLengthInDays = rand(1, 50);
             $priceBeginDate = $currentDay;
             $priceEndDate = $priceBeginDate->copy()->addDays($priceLengthInDays);
             array_push($data, [
                 'item_id' => $itemId,
                 'price' => fake()->randomFloat(2, 200, 1000),
                 'price_start_date' => $priceBeginDate->toDateString(),
                 'price_end_date' => $priceEndDate->toDateString()
             ]);
             $currentDay = $priceEndDate->copy()->addDay();
         }
         DB::table('prices')->insert($data);
     }

    public function visible(){
        return $this->state(function($attributes){
            return [
                'visibleInCatalog' => true,
                'amount' => rand(1,10)
            ];
        })->afterCreating(function(Item $item){
            Image::factory()->count(rand(2, 5))->create([
                'parent_id' => $item->id,
                'type' => 'item'
            ]);
            $this->generatePrices($item->id);
        });
    }
    public function hiddenBySeller(){
        return $this->state(function($attributes){
            return [
                'amount' => rand(1,10)
            ];
        })->afterCreating(function(Item $item){
            Image::factory()->count(rand(2, 5))->create([
                'parent_id' => $item->id,
                'type' => 'item'
            ]);
            $this->generatePrices($item->id);
        });
    }

    public function hiddenWithoutImages(){
        return $this->state(function($attributes){
            return [
                'visibleInCatalog' => false,
                'amount' => rand(1,10)
            ];
        })->afterCreating(function(Item $item){
            $this->generatePrices($item->id);
        });
    }

    public function hiddenWithoutPrices(){
        return $this->state(function($attributes){
            return [
                'visibleInCatalog' => false,
                'amount' => rand(1,10)
            ];
        })->afterCreating(function(Item $item){
            Image::factory()->count(rand(2, 5))->create([
                'parent_id' => $item->id,
                'type' => 'item'
            ]);
        });
    }

    public function hiddenWithoutAmount(){
        return $this->state(function($attributes){
            return [
                'visibleInCatalog' => false,
                'amount' => 0
            ];
        })->afterCreating(function(Item $item){
            Image::factory()->count(rand(2, 5))->create([
                'parent_id' => $item->id,
                'type' => 'item'
            ]);
            $this->generatePrices($item->id);
        });
    }

    public function definition(): array
    {
        $categories = DB::table('categories')->where('parent_id', '>', '0')->pluck('id');
        $names = DB::table('placeholders')->where('type', 'name')->pluck('value');
        $descriptions = DB::table('placeholders')->where('type', 'description')->pluck('value');
        return [
            'name' => fake()->randomElement($names),
            'description' => fake()->randomElement($descriptions),
            'amount' =>  0,
            'category_id' => fake()->randomElement($categories),
            'visibleInCatalog' => false
        ];
    }
}
