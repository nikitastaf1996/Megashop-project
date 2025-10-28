@extends('main')
@section('title', 'Войдите в аккаунт')
@section('content')
<form class="flex flex-col  sm:w-1/4 gap-3" name="main-form" data-path='/login' data-handler='message' data-component='form'>
    <h1>Войдите в аккаунт</h1>
    <h3 class="text-red-500 hidden form-message">Сообщение</h3>
    <input placeholder="Email" name="email" type="text" class="text-lg text-[#222] placeholder:text-[rgb(140,140,140)] leading-relaxed  p-1 outline-none border-2 border-brand"></input>
    <input placeholder="Пароль" name='password' type="password" class="text-lg text-[#222] placeholder:text-[rgb(140,140,140)] leading-relaxed  p-1 outline-none border-2 border-brand"></input>
    <a href='/register'>Зарегистроваться</a>
    <a href="/forgot-password">Восстановить Пароль</a>
    <div class="flex action justify-center h-20 items-center cursor-pointer  bg-brand">
        <p class="text-[1.5em] action cart_order font-bold">Войти в аккаунт</p>
    </div>
</form>
@endsection