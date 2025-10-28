@extends('main')
@section('title', 'Регистрация')
@section('content')
<form class="flex flex-col w-1/4 gap-3" name='main-form' data-path='/register' data-handler='message' data-component='form'>
    <h1>Регистрация</h1>
    <h3 class="text-red-500 hidden form-message">Сообщение</h3>
    <input placeholder="Имя" name="name" type="text" class="text-lg text-[#222] placeholder:text-[rgb(140,140,140)] leading-relaxed  p-1 outline-none border-2 border-brand"></input>
    <input placeholder="Email" name="email" type="text" class="text-lg text-[#222] placeholder:text-[rgb(140,140,140)] leading-relaxed  p-1 outline-none border-2 border-brand"></input>
    <input placeholder="Пароль" name="password" type="password" class="text-lg text-[#222] placeholder:text-[rgb(140,140,140)] leading-relaxed  p-1 outline-none border-2 border-brand"></input>
    <input placeholder="Подтвердите пароль" name='password_confirmation' type="password" class="text-lg text-[#222] placeholder:text-[rgb(140,140,140)] leading-relaxed  p-1 outline-none border-2 border-brand"></input>
    <div class="flex action justify-center h-20 items-center cursor-pointer  bg-brand">
        <p class="text-[1.5em] action cart_order font-bold">Зарегистроваться</p>
    </div>
</form>
@endsection