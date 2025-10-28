@extends('main')
@section('title', 'Каталог')
@section('content')


@if(count($banners) != 0)
    <div data-component="slider" data-slider-type="sides" class="w-full hidden sm:block relative">
    <div class="slider-main-elements relative">
        @foreach ($banners as $banner)
        @if($banner->type == 'singular_item')
        <a class="slider-element  w-full h-full @if ($loop->first) block @else hidden @endif bg-brand pl-[100px] pr-[100px] pt-[50px] pb-[50px]" href="{{ url('/product', [$banner->id]) }}">
            <div class="font-bold select-none text-[20px] h-[30px] mb-[10px] lg:w-[700px] w-[400px] truncate ">
                {{ $banner->name }}</div>
            <div class="w-[300px]  select-none text-[12px] line-clamp-5	 h-[90px]">{{ $banner->description, 0, 250 }}
            </div>
            <div class="text-red-600 select-none font-bold text-[50px]">{{ $banner->prices[0]->price }} ₽</div>
            <image class="absolute object-contain select-none hidden lg:block w-[30%] h-[80%] top-[10%] right-[120px]" src="{{ asset('storage/' . $banner->images[0]->image_path) }}">
        </a>
        @endif
        @endforeach
    </div>
    <div class="slider-controls">
        <div class="slider-control forward-button  select-none py-[15px] px-[23px] cursor-pointer z-10 bg-gray-500/40 hover:bg-gray-500/60 rounded-full absolute  top-[40%] right-[50px] text-center">
            <i class="slider-control forward-button fa-solid fa-forward-step"></i>
        </div>
        <div class="slider-control backward-button select-none py-[15px] px-[23px] cursor-pointer z-10 bg-gray-500/40 hover:bg-gray-500/60 rounded-full absolute  top-[40%] left-[30px] text-center">
            <i class="slider-control backward-button fa-solid fa-backward-step"></i>
        </div>
    </div>
</div>
@endif

<h1 id="page_name" class="font-bold uppercase text-[28px]"><a class="block hover:bg-brand" href="{{url('/catalog')}}#page_name">Каталог <i class="fa-solid fa-arrow-down"></i> </a>
    @if ($category != null)
    @if ($category->parent_id == 0)
    <a class="block hover:bg-brand" href="{{url('/catalog',[$category->id])}}#page_name">{{ $category->name }} <i class="fa-solid fa-arrow-down"></i></>
    @else
    <a class="block hover:bg-brand" href="{{url('/catalog',[$category->parent->id])}}#page_name">{{ $category->parent->name }} <i class="fa-solid fa-arrow-down"></i></a> <a class="block">{{ $category->name }} </a>
    @endif
    @endif
</h1>
<div class="flex flex-row flex-wrap gap-6 justify-around sm:justify-center">
    <x-item-list :items="$items" :rating="true" class="w-[150px] sm:w-[200px] gap-3" />
    @if ($items != null)
    {{ $items->fragment('page_name')->links() }}
    @endif
</div>
</div>
@endsection
