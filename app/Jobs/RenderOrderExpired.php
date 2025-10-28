<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class RenderOrderExpired implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    /**
     * Create a new job instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = $this->order->loadMissing('order_items.item','order_items.reservation');
        if($order->is_payed){
            $reservations = array_values($order->order_items->pluck('reservation')->pluck('id')->toArray());
            Reservation::wherein('id',$reservations)->update(['status'=>'fullfilled']);
        }
        else{
            foreach ($order->order_items as $key => $value) {
                $value->reservation->status = 'expired';
                $value->item->amount = $value->item->amount + $value->reservation->amount;
                if(!$value->item->visibleInCatalog){
                    $value->item->visibleInCatalog = 1;
                }
                $value->reservation->save();
                $value->item->save();
            };
            $order->status = 'order_expired';
            $order->save();
        }
    }
}
