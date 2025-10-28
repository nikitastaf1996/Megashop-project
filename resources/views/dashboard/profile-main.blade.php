@extends('main')
@section('title', 'Профиль')
@section('content')

<div class="flex-col flex sm:flex-row h-screen">
    
    <!-- Sidebar -->
    <div class="sm:w-64 bg-white shadow-lg p-4 flex flex-col">
      <h1 class="text-2xl font-bold sm:mb-6">Профиль</h1>
      
      <nav class="flex flex-col sm:gap-2">
        <a href="/user/orders" class="sm:px-4 sm:py-2 rounded-lg ">Заказы</a>
        <a href="/user/settings" class="sm:px-4 sm:py-2 rounded-lg ">Настройки</a>
        <a href="/logout" class="sm:px-4 sm:py-2 rounded-lg">Выйти</a>
      </nav>
    </div>
    
    <!-- Main Content -->
    <div class="sm:flex-1 p-6">
      @yield('content-dashboard')
    </div>
</div>
@endsection