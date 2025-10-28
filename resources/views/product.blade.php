@extends('main')
@section('title', $item == null ? 'Товар не найден' : $item->name)
@section('content')
    @if ($item !== null)
        <h1 class="font-bold text-[28px] mb-3">{{ $item->name }}</h1>
        <div class="flex flex-row w-full h-8 mb-3 items-center">
            <a class="flex w-8 h-full mr-3 bg-lime-500" href="{{ url('/product/' . $item->id . '/reviews') }}">
                <p class="m-auto">{{ number_format($item->reviews_avg_grade, 1) }}</p>
            </a>
            <a class="text-[#04b] hover:text-[#c70000]"
                href="{{ url('/product/' . $item->id . '/reviews') }}">{{ $item->reviews_count }} отзывов</a>
        </div>
        @php
            if (count($item->images) == 0) {
                $item->images[0] = new StdClass();
                $item->images[0]->name = 'Unavailable image';
                $item->images[0]->image_path = '/placeholders/default.png';
            }
        @endphp
        <div class=" sm:h-[320px] gap-3 flex flex-col sm:flex-row sm:flex-wrap">
            <div data-component="slider" data-slider-type='switcher' class="w-full sm:w-[200px] lg:w-[300px] h-[300px] items-center flex-row flex">
                <div class="slider-controls flex overflow-hidden flex-col w-[100px] h-full">
                    @foreach ($item->images->take(4) as $image)
                        <image loading='lazy' data-id='{{ $loop->index }}' alt="{{ $item->name }}"
                            class="object-contain slider-control h-1/4 w-full @if ($loop->first) border-[#fc0] border-4 border-solid @endif hover:border-[#fc0] hover:border-4 hover:border-solid"
                            src="{{ asset('storage/' . $image->image_path) }}"></image>
                    @endforeach
                </div>
                <div class="relative w-full h-full slider-main-elements">
                    @foreach ($item->images as $image)
                        <image
                            class="slider-element object-contain absolute top-0 left-0 w-full h-full @if ($loop->first) block
            @else
            hidden @endif"
                            src="{{ asset('storage/' . $image->image_path) }}"></image>
                    @endforeach
                </div>

            </div>
            <div class="w-full h-full sm:w-1/4">
                <h1 class="font-bold text-[14px]">Описание</h1>
                <p class="overflow-hidden text-[13px]">{{ $item->description }}</o>
            </div>
            <div class="w-full h-full bg-gray-100 sm:w-2/5">
                @if ($item->itemUnavailable)
                    <h1 class="font-bold text-violet-700 text-[28px]">
                        Товар недоступен
                    </h1>
                @else
                    <h1 class="font-bold text-[28px]">
                        {{ $item->prices->last()->price }} Р
                    </h1>
                @endif
                @if ($item->itemUnavailable)
                    <x-item__buttons :item="$item" :disabled="true" class="h-[34px]" />
                @else
                    <x-item__buttons :item="$item" class="h-[34px]" />
                @endif

                
            </div>
        </div>

        @if ($item->reviews_count > 0)
                <div class="flex flex-row items-center sm:w-full w-1/2">
                    <h1 class="font-bold text-[28px] mr-3">ОТЗЫВЫ</h1>
                    <a class="text-[#999999] text-[28px] hover:text-red-500"
                        href="{{ url('product/' . $item->id . '/reviews') }}">{{ $item->reviews_count }}</a>
                </div>
                <div class="flex flex-col w-full sm:w-1/2 gap-3">
                @foreach ($item->reviews as $review)
                       <div class="border border-solid border-black">
                        <div class="w-full h-14 flex flex-row items-center">
                            <img class="h-full object-contain aspect-square rounded-full"
                                src="{{ asset('storage/' . $review->user->image->image_path) }}"/>
                            <div class="h-1/2 flex flex-row items-center ">
                                    @for ($i = 0; $i < round($review->grade); $i++)
                                    <i class="fa-solid  fa-star" style="color: #FFD43B;"></i>
                                    @endfor
                                    @for ($i = 0; $i < (5-round($review->grade)); $i++)
                                    <i class="fa-solid fa-star"></i>
                                    @endfor
                            </div>
                            <p class="text-sm font-semibold">{{ $review->user->name }}</p>
                        </div>
                        
                        <image class="h-[200px] w-[200px] object-contain" src="{{ asset('storage/' . $review->images[0]->image_path) }}">
                        <div class="w-full text-sm leading-none">
                                {{ $review->text }}
                        </div>
        </div>
                @endforeach
                </div>
                <a class="text-[24px] hover:text-red-500" href="{{ url('product/' . $item->id . '/reviews') }}">Перейти к
                    отзывам</a>
            </div>
        @endif
    @else
        <h1 class="font-bold text-center text-[28px] w-full mb-3">Товар не был найден</h1>
        <p class="text-center w-full text-[14px] mb-4 ">Но у нас еще есть много других товаров</p>
        <div class="flex flex-row flex-wrap justify-center gap-6">
            <x-item-list :items="$items" class="w-[200px] gap-3" />
        </div>
    @endif

@endsection
