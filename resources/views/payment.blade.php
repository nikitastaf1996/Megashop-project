@extends('main')
@section('title', 'Оплата')
@section('content')
<div class="flex-col">
    <h1 class="font-bold uppercase text-[28px]">Товар на сумму {{$price}} был оплачен</h1>
    <a href="/order/confirmOrder">Увидеть поздравление с успешным заказом</a>
</div>
@endsection