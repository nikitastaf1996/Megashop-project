@extends('main')
@section('title', 'Избранное')
@section('content')
<div data-component="favorite">
    <h1 class="font-bold uppercase text-[28px]">Избранное</h1>
    @if($items)
    <div class="flex flex-row flex-wrap gap-6 justify-around sm:justify-center">
    <x-item-list :items="$items" :rating="false" class="w-[150px] sm:w-[200px] gap-3" />
    @else
        <h1 class="font-bold uppercase text-[28px]">В избранном пока пусто</h1>
        <h2>Но у нас большой выбор товаров которые вам точно понравятся</h2>
    @endif
</div>
</div>
@endsection