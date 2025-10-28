@props(['class' => '', 'item' => null, 'disabled' => false])
<div data-component="cart_buttons" data-id="{{ $item->id }}" @class([
    'flex',
    'select-none',
    'cursor-pointer',
    $class,
])>
@if ($disabled == true)
    <div class="flex h-full flex-1 bg-violet-700">
        <p class="m-auto text-[1em] text-white font-bold">Недоступно</p>
    </div>
@else
    @if (session('cart.items.' . $item->id . '.amount'))
        <div class="cart_remove flex flex-1 h-full bg-brand">
                @if (session('cart.items.' . $item->id . '.amount') == 1)
                    <i class="fa-solid m-auto fa-trash cart_remove"></i>
                @else
                    <i class="fa-solid m-auto cart_remove fa-minus"></i>
                @endif
        </div>
        <div class="flex flex-1 h-full">
            <p class="m-auto text-[1.28em] font-bold text-center">{{ session('cart.items.' . $item->id . '.amount') }}
            </p>
        </div>
        @if (session('cart.items.' . $item->id . '.amount') + 1 > $item->amount)
        @else
            <div class="cart_add items-center justify-center flex-1 h-full flex bg-brand">
                <i class="fa-solid fa-plus cart_add"></i>
            </div>
        @endif
    @else
        <div class="cart_add flex justify-center items-center flex-1 h-full  bg-brand">
            <p class="hidden sm:block text-[1em] font-bold cart_add">Добавить в корзину</p>
            <i class="sm:!hidden cart_add fa-solid fa-cart-shopping "></i>
        </div>
    @endif
@endif
<div class="cart_favorite flex justify-center items-center h-full w-1/3  bg-brand">
            @if (session('cart.favorites.' . $item->id) == null)
                <i class="cart_favorite  fa-solid fa-heart"></i>
            @else
                <i class="cart_favorite  fa-solid fa-heart" style="color: #ff0000;"></i>
            @endif
</div>
</div>