<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itemAmount = 1000;
        $itemVisible = $itemAmount * 0.7;
        Item::factory()->count($itemVisible)->visible()->create();
        $itemHiddenOverall = $itemAmount *0.3;
        $itemHiddenBySeller = $itemHiddenOverall * 0.5;
        $itemHiddenByShop = $itemHiddenOverall * 0.5;
        Item::factory()->count($itemHiddenBySeller)->hiddenBySeller()->create();
        $itemHiddenWithoutImages =  $itemHiddenByShop*0.3;
        Item::factory()->count($itemHiddenWithoutImages)->hiddenWithoutImages()->create();
        $itemHiddenWithoutPrices =  $itemHiddenByShop*0.3;
        Item::factory()->count($itemHiddenWithoutPrices)->hiddenWithoutPrices()->create();
        $itemHiddenWithoutAmount = $itemHiddenByShop *0.3;
        Item::factory()->count($itemHiddenWithoutAmount)->hiddenWithoutAmount()->create();
    }
}
