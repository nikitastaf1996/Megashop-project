<?php

namespace App\Http\Controllers;

use App\Jobs\RenderOrderExpired;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Price;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class OrderController extends Controller
{
    public function checkOrder(Request $request)
    {
        if (!empty(session()->get('cart.items'))) {
            DB::beginTransaction();
            $itemsFromDB = DB::table('items')->whereIn('id', array_keys(session()->get('cart.items')))->lockForUpdate()->get();
            $pricesFromDB = Price::whereIn('item_id', array_keys(session()->get('cart.items')))->where('price_start_date', '<=', now()->toDateString())
                ->where('price_end_date', '>=', now()->toDateString())->get();
            $item_updates = [];
            foreach (session()->get('cart.items') as $key => $item) {
                $itemFromDB = $itemsFromDB->where('id', $key)->first();

                if (!$itemFromDB->visibleInCatalog) {
                    session()->forget('cart.items' . '.' . $key);
                    array_push($item_updates, 'Товар ' . $itemFromDB->name . ' не доступен для продажи на текущий момент');
                    break;
                }

                if ($itemFromDB->amount == 0) {
                    session()->forget('cart.items' . '.' . $key);
                    array_push($item_updates, 'Товар ' . $itemFromDB->name . ' закончился прежде чем вы смогли его купить');
                    break;
                }

                if ($item['amount'] > $itemFromDB->amount) {
                    session()->put('cart.items.' . $key . '.amount', $itemFromDB->amount);
                    array_push($item_updates, 'Товар' . $itemFromDB->name . ' не был доступен в нужном вам количестве. Количество было обновлено');
                    break;
                }
            }
            if (empty($item_updates)) {
                $total_order_price = 0;
                foreach (session()->get('cart.items') as $key => $item) {
                    $priceFromDB = $pricesFromDB->where('item_id', $key)->first();
                    $total_order_price+= $priceFromDB->price * $item['amount'];
                }
                $order = new Order();
                $order->user_id = Auth::id();
                $order->total_price = $total_order_price;
                $order->save();
                foreach (session()->get('cart.items') as $key => $item) {
                    $priceFromDB = $pricesFromDB->where('item_id', $key)->first();
                    $orderItem = new OrderItem;
                    $orderItem->price = $priceFromDB->price;
                    $orderItem->item_id = $key;
                    $orderItem->amount = $item['amount'];
                    $orderItem->order_id = $order->id;
                    $orderItem->save();
                    $itemFromDB = $itemsFromDB->where('id', $key)->first();
                    if($itemFromDB->amount - $item['amount'] == 0){
                        $visibleInCatalog = 0;
                    }
                    else{
                        $visibleInCatalog = 1;
                    }
                    Item::where('id', $key)->update([
                        'amount' => $itemFromDB->amount - $item['amount'],
                        'visibleInCatalog' => $visibleInCatalog
                    ]);
                    Db::table('reservations')->insert([
                        'order_item_id' =>$orderItem->id,
                        'amount' => $item['amount'],
                        'expiration_time' => Carbon::now()->addHours(2)->toDateTimeString(),
                    ]);
                    RenderOrderExpired::dispatch($order)->delay(now()->addSeconds(60));
                }

                session()->forget('cart.items');
                DB::table('sessions')->where('user_id', Auth::id())->delete();
                DB::commit();
                return response()->spa(redirect()->route('payment',[
                    'item' => $order->id
                ]));
            } else {
                DB::rollBack();
                session()->flash('cartnotification', implode('\n', $item_updates));
                return response()->spa(redirect('/cart'));
            }
        } else {
            return response()->spa(redirect('/cart'));
        }
    }
    public function confirmOrder(Request $request)
    {
        return response()->spa(view('orderconfirmation'));
    }
}
