@extends('main')
@section('title', $item == null ? 'Товар не найден' : $item->name)
@section('additionalJs')
@endsection
@section('content')
    @if ($item !== null)
        <h1 class="font-bold text-[28px] w-full mb-3">{{ $item->name }}</h1>
        <div class="flex flex-row w-full h-8 mb-3">
            <a class="flex w-8 h-full mr-3 bg-lime-500" href="{{ url('/product/' . $item->id . '/reviews') }}">
                <p class="m-auto">{{ number_format($item->reviews_avg_grade, 1) }}</p>
            </a>
            <a class="my-auto text-[#04b] hover:text-[#c70000]"
                href="{{ url('/product/' . $item->id . '/reviews') }}">{{ $item->reviews_count }} отзывов</a>
        </div>
        <div class=" sm:h-[320px] gap-3 flex flex-col sm:flex-wrap">
            <div data-slider-type='switcher' class="w-full sm:w-[200px] lg:w-[300px] h-[300px] items-center flex-row flex">
                <div class="slider-controls overflow-hidden flex-col gap-3 w-[100px] h-[300px] sm:h-full">
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
                @if ($item->priceUnknown)
                    <div class="w-full font-bold text-violet-700 text-[28px]">
                        Цена неизвестна
                    </div>
                @else
                    <div class="w-full font-bold text-[28px]">
                        {{ $item->prices->last()->price }} Р
                    </div>
                @endif
                @if ($item->priceUnknown)
                    <x-item__buttons :item="$item" :disabled="true" class="h-[34px]" />
                @else
                    <x-item__buttons :item="$item" class="h-[34px]" />
                @endif
            </div>
        </div>
        @if ($reviews)
            <div class="w-full">
                <div class="flex flex-row items-center w-full">
                    <h1 class="font-bold text-[28px] mr-3">Отзывы</h1>
                    <a class="text-[#999999] text-[24px] hover:text-red-500"
                        href="{{ url('product/' . $item->id . '/reviews') }}">{{ $item->reviews_count }}</a>
                </div>
                @foreach ($reviews as $review)
                    <div class="flex flex-col">
                        <div class="w-full h-14 flex flex-row items-center">
                            <image class="h-full object-contain aspect-square"
                                src="{{ asset('storage/' . $review->user->image->image_path) }}">
                                <div class="h-full w-1/4 flex flex-row">
                                    @for ($i = 0; $i < round($review->grade); $i++)
                                        <image class="h-full w-1/5 aspect-square" src="{{ asset('images/star.svg') }}">
                                    @endfor
                                </div>

                                <p class="text-lg">{{ $review->user->name }}</p>
                        </div>
                        <image class="h-[300px] w-[300px]" src="{{ asset('storage/' . $review->images[0]->image_path) }}">
                            <div class="w-full">
                                {{ $review->text }}
                            </div>

                    </div>
                @endforeach
                {{ $reviews->links() }}
            </div>
        @else
            Отзывов нет
        @endif
    @else
        <h1 class="font-bold text-center text-[28px] w-full mb-3">Товар не был найден</h1>
        <p class="text-center w-full text-[14px] mb-4 ">Но у нас еще есть много других товаров</p>
        <div class="flex flex-row flex-wrap justify-center gap-6">
            <x-item-list :items="$items" class="w-[200px] gap-3" />
        </div>
    @endif
@endsection
