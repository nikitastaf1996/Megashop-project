@extends('main')
@section('title', 'Восстановить Пароль')
@section('content')
<form class="flex flex-col w-1/4 gap-3" name='main-form' data-path='/forgot-password' data-handler='message' data-component='form'>
    <h1>Восстановить Пароль</h1>
    <h3 class="text-red-500 hidden form-message">Сообщение</h3>
    <input placeholder="Email" name='email' type="text" class="text-lg text-[#222] placeholder:text-[rgb(140,140,140)] leading-relaxed  p-1 outline-none border-2 border-brand"></input>
    <a href='/register'>Зарегистроваться</a>
    <a href="/login">Войти в аккаунт</a>
    <div class="flex action justify-center h-20 items-center cursor-pointer  bg-brand">
        <p class="text-[1.5em] action cart_order font-bold">Восстановить Пароль</p>
    </div>
</form>
@endsection