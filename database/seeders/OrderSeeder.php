<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = Item::all()->pluck('id');
        $users = User::all()->pluck('id');
        for ($i = 0; $i < 250; $i++) {
            $amountOFOrdersItemsPerOrder = rand(1, 10);
            $user = fake()->randomElement($users);
            $order_items = [];
            for ($y = 0; $y < $amountOFOrdersItemsPerOrder; $y++) {
                array_push($order_items, [
                    'item_id' => fake()->randomElement($items),
                    'amount' => rand(1, 10),
                    'price' => fake()->randomFloat(2, 200, 1000)
                ]);
            }
            $order = new Order();
            $order->user_id = $user;
            $order->total_price = array_sum(
                array_map(fn($item) => $item['price'] * $item['amount'], $order_items)
            );
            $order->save();
            foreach ($order_items as $key => $value) {
                $order_items[$key]['order_id'] = $order->id;
            }
            DB::table('order_items')->insert($order_items);
        }
    }
}
