<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Cache::forget('banners'); 

        $top_orders = DB::table('items')->leftjoin('order_items','items.id','=','order_items.item_id')
        ->where('items.visibleInCatalog','=','1')
        ->whereNotNull('order_items.id')
        ->groupBy('items.id')
        ->orderByDesc('ordersAmount')
        ->orderByDesc('overAllPrice')
        ->limit(10)
        ->selectRaw('items.id,
  COUNT(order_items.id) as ordersAmount,
  sum(order_items.price) as overAllPrice')->get();
        foreach($top_orders->pluck('id') as $sale){
            Banner::create([
                'banner_information' =>json_encode([
                    "item_id" => $sale
                ]),
                "date_start" => Carbon::now()->startOfDay()->toDateTimeString(),
                'date_end' => Carbon::now()->endOfDay()->toDateTimeString(),
                'type' => 'singular_item'
            ]);
        }
    }
}
