@extends('dashboard.profile-main')
@section('title', 'Настройки')
@section('content-dashboard')
<div class="flex flex-col gap-3" name="main-form" data-path='/login' data-handler='message' data-component='form'>
    <h1>Настройки</h1>
    <h3 class="text-red-500 hidden form-message">Сообщение</h3>
    <a href="{{ url('user/settings/update/promotional') }}" class="flex h-8 select-none cursor-pointer  bg-brand w-1/3">
        <input disabled="true" @checked($user->promotional) class="h-full select-none cursor-pointer w-[30px]" type="checkbox"> <p class="m-auto text-[1em] font-bold">Присылать email с рекомендациями</p>
    </a>
    <a href="{{ url('user/settings/update/deleteAccount')}}" class="flex h-8 select-none cursor-pointer  bg-red-700 w-1/3">
      <p class="m-auto text-[1em] text-white font-bold">Удалить аккаунт</p>
    </a>
</div>
@endsection