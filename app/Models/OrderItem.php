<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    public function item(){
        return $this->hasOne(Item::class,'id','item_id');
    }
    public function reservation(){
        return $this->hasOne(Reservation::class,'order_item_id','id');
    }
}

