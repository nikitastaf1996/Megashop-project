@extends('main')
@section('title', 'Восстановление пароля')
@section('content')
<form class="flex flex-col w-1/4 gap-3" name="main-form" data-path='{{ route('password.store') }}' data-handler='message' data-component='form'>
    <h1>Востановление пароля/h1>
    <h3 class="text-red-500 hidden form-message">Сообщение</h3>
    <input placeholder="Пароль" name='password' type="password" class="text-lg text-[#222] placeholder:text-[rgb(140,140,140)] leading-relaxed  p-1 outline-none border-2 border-brand"></input>
    <input placeholder="Подтвердите пароль" name='password_confirmation' type="password" class="text-lg text-[#222] placeholder:text-[rgb(140,140,140)] leading-relaxed  p-1 outline-none border-2 border-brand"></input>
    <input name='token' value="{{ $request->token }}" type="hidden">
    <!-- <input name='email' value="{{ $request->email }}" type="hidden"> -->

    <input name='email' value="nikitastaf1996@gmail.com" type="hidden">
    <div class="flex action justify-center h-20 items-center cursor-pointer  bg-brand">
        <p class="text-[1.5em] action cart_order font-bold">Обновить пароль</p>
    </div>
</form>
@endsection

