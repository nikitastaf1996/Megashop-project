@extends('main')
@section('title', 'Войдите в аккаунт')
@section('content')
<div class="flex flex-col w-1/4 gap-3">
    <h1>Войдите в аккаунт</h1>
    <input placeholder="Email" type="text" class="text-lg text-[#222] placeholder:text-[rgb(140,140,140)] leading-relaxed  p-1 outline-none border-2 border-brand"></input>
    <input placeholder="Пароль" type="text" class="text-lg text-[#222] placeholder:text-[rgb(140,140,140)] leading-relaxed  p-1 outline-none border-2 border-brand"></input>
    <div class="flex cart_order justify-center h-20 items-center cursor-pointer  bg-brand">
        <p class="text-[1.5em] cart_order font-bold">Войти в аккаунт</p>
    </div>
</div>
@endsection