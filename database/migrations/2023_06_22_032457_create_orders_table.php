<?php

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamp('datetime')->useCurrent();
            $table->float('total_price',10,2);
            $table->enum('status',['order_created','order_payed','order_expired'])->default('order_created');
            $table->foreignIdFor(User::class);
            $table->boolean('is_payed')->default(0);
            $table->string('merchant_status')->nullable();
            $table->timestamps();
        });
        Schema::create('order_items',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger("order_id")->nullable(false);
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreignIdFor(Item::class);
            $table->timestamp('datetime')->useCurrent();
            $table->integer('amount');
            $table->float("price",10,2);
            $table->timestamps();
        });
        Schema::create('reservations',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger("order_item_id")->nullable(false);
            $table->foreign('order_item_id')->references('id')->on('order_items');
            $table->integer('amount');
            $table->dateTime('expiration_time');
            $table->string('status')->default('active');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('orderitems');
        Schema::dropIfExists('orders');
        
    }
};
