@extends('main')
@section('title', 'Корзина')
@section('content')
<div data-component="cart">
    @if($temporary_cart)
    <h1 class="font-bold uppercase text-[28px]">Временная Корзина</h1>
    <div class="flex flex-col gap-10 lg:gap-0 lg:flex-row w-full">

        <div class="flex flex-col flex-wrap w-full lg:w-3/5 gap-3">
            <x-item-list :items="$temporary_cart" :horizontal="true" :cart_buttons="false" class="h-[130px]" />
        </div>
        <div class="w-full flex flex-col self-start lg:w-2/5 bg-violet-700 gap-10">
            <a href="{{ url('/cart/mergeTemp') }}" class="flex justify-center h-20 items-center cursor-pointer  bg-brand">
                <p class="text-[1.5em] cart_order font-bold text-black">Обьеденить корзины</p>
            </a>
            <a href="{{ url('/cart/removeTemp') }}" class="flex justify-center h-20 items-center cursor-pointer  bg-black">
                <p class="text-[1.5em] cart_order font-bold text-white">Удалить временную корзину</p>
            </a>
        </div>

    </div>
    @endif
    @if ($items)
    <h1 class="font-bold uppercase text-[28px]">Корзина</h1>
    @if(session('cartnotification'))
    <h3 class="text-red-600">{{session('cartnotification')}}</h3>
    @endif
    <div class="flex flex-col gap-10 lg:gap-0 lg:flex-row w-full">
        <div class="flex flex-col flex-wrap w-full lg:w-3/5 gap-3">
            <x-item-list :items="$items" :horizontal="true" class="h-[130px]" />
        </div>
        <div class="w-full flex flex-col self-start lg:w-2/5 bg-brand">
            <h3 class="font-bold">{{$cartInformation['itemCount']}} {{trans_choice('Товар|Товаров',$cartInformation['itemCount'])}} - {{$cartInformation['itemSum']}} {{trans_choice('Рублей|Рубля',$cartInformation['itemSum'])}}</h3>
            <h1 class="text-lg">ИТОГО</h1>
            <h1 class="text-3xl font-bold">{{$cartInformation['itemSum']}} ₽</h1>
            <a href="{{ url('/order/checkOrder') }}" class="flex cart_order justify-center h-20 items-center cursor-pointer  bg-black">
                <p class="text-[1.5em] cart_order font-bold text-white">Оформить Заказ</p>
            </a>
        </div>
    </div>
    @else
    @if(session('cartnotification'))
    <h3 class="text-red-600">{{session('cartnotification')}}</h3>
    @endif
    <h1 class="font-bold text-center text-[28px]">Корзина пуста</h1>
    <p class="text-center w-full text-[14px] mb-4 ">Вот товары которые могут быть вам интересны</p>
    <div class="flex flex-row flex-wrap justify-center gap-6">
        <x-item-list :items="$recomendations" :rating="true" class="w-[200px] gap-1" />
    </div>
    @endif
</div>
@endsection