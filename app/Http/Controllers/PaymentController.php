<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function createPaymentLinkForAllItems(Request $request)
    {
        $orders = Auth::user()->ordersToPay;
        $totalSum = $orders->pluck('total_price')->sum();
        $paymentLinkUUID = Str::uuid();
        $payments = [];
        foreach ($orders as $key => $value) {
            array_push($payments, [
                'payment_id' => $paymentLinkUUID,
                'order_id' => $value['id']
            ]);
        }
        DB::table('payments')->insert($payments);
        return response()->spa(redirect()->route('paymentSimulation',[
            'payment_uuid'=> $paymentLinkUUID,
            'price' => $totalSum
        ]));
    }
    public function createPaymentLinkForOneItem(Request $request) {
        $order = Order::where('id',$request->item)->get()->first();
        $paymentLinkUUID = Str::uuid();
        DB::table('payments')->insert([
            'payment_id'=> $paymentLinkUUID,
            'order_id' => $order->id
        ]);
        return response()->spa(redirect()->route('paymentSimulation',[
            'payment_uuid'=> $paymentLinkUUID,
            'price' => $order->total_price
        ]));
    }

    public function simulatePayment(Request $request) {
        $order_ids = DB::table('payments')->where('payment_id',$request->payment_uuid)->get()->pluck('order_id');
        Order::whereIn('id',$order_ids)->update([
            'status' => 'order_payed',
            'is_payed' =>1
        ]);
        return response()->spa(view('payment',[
            'price' => $request->price
        ]));
    }
}
