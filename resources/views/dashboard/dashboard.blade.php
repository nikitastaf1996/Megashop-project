@extends('dashboard.profile-main')
@section('title', 'ЗАКАЗЫ')
@section('content-dashboard')
<h1 class="font-bold uppercase text-[28px]">ЗАКАЗЫ</h1>
@foreach($orders as $order)
<div class="flex flex-col">
    <h2>Заказ от {{$order->datetime}} на сумму {{$order->total_price}}</h2>
    <div class="flex overflow-x-auto w-full">
        @foreach($order->order_items as $orderItem)
        <a class="w-[100px] w-min-[100px]" href="{{ url('/product', [$orderItem->item->id]) }}">
            <img class="w-[100px] w-min-[100px] object-contain aspect-square" src="{{asset('storage/' . $orderItem->item->images[0]->image_path)}}">
        </a>
        @endforeach
    </div>
    @switch($order->status)
    @case('order_created')
    <a href="{{ url('/payment', [$order->id]) }}" class="flex select-none cursor-pointer h-[34px] bg-red-700 w-1/3">
        <p class="m-auto text-[1em] text-white font-bold">Оплатить</p>
    </a>
    @break
    @case('order_expired')
    <div  class="flex select-none cursor-pointer h-[34px] bg-violet-700 w-1/3">
        <p class="m-auto text-[1em] text-white font-bold">Заказ истек</p>
    </div>
    @break
    @case('order_payed')
    <div  class="flex select-none cursor-pointer h-[34px] bg-violet-700 w-1/3">
        <p class="m-auto text-[1em] text-white font-bold">Оплачено</p>
    </div>
    @break
    @endswitch

</div>
@endforeach
@endsection