@props(['horizontal' => false, 'rating' => false,'cart_buttons'=>true, 'items' => null, 'class' => ''])
@if ($items != null)
    @foreach ($items as $item)
        <div data-id="{{ $item->id }}" @class([
            'flex',
            'flex-col',
            'text-sm',
            'flex-wrap' => $horizontal,
            $class,
        ])>
            <a @class(['h-full' => $horizontal]) href="{{ url('/product', [$item->id]) }}">
                <image  @class(['object-contain', 'aspect-square','w-full'=>!$horizontal, 'h-full' => $horizontal])
                    src="{{ asset('storage/' . $item->images[0]->image_path) }}">
                </image>
            </a>
            <a @class([
                'text-[1em]',
                'h-1/4' => $horizontal,
                'w-full' => $horizontal,
                'h-[2.5em]' => !$horizontal,
                'overflow-hidden',
                'text-ellipsis',
                'font-bold',
            ]) href="{{ url('/product', [$item->id]) }}">{{ $item->name }}</a>
            @if ($rating == true)
                <a class="flex h-[1.7em]" href="{{ url('/product', [$item->id]) }}">
                    @if (!$item->reviews_avg_grade == null && !$item->reviews_count == 0)
                        <div class="flex w-1/2 items-center ">
                            @for ($i = 0; $i < round($item->reviews_avg_grade); $i++)
                            <i class="fa-solid w-1/5 fa-star text-xl" style="color: #FFD43B;"></i>
                                
                            @endfor
                        </div>
                        <p class="w-1/2 text-[1.4em] font-bold text-right">
                            {{ number_format($item->reviews_avg_grade, 1) }}/{{ $item->reviews_count }}</p>
                    @endif
                </a>
            @endif
            <a @class([
                'text-[1.4em]',
                'font-bold',
                'h-1/4' => $horizontal,
                'w-full' => $horizontal,
            ]) href="{{ url('/product', [$item->id]) }}">{{ $item->prices[0]->price }}
                ₽</a>
            @if($cart_buttons)
            @if ($horizontal)
                <x-item__buttons :item="$item" class="w-1/2 mt-auto sm:1/3 h-1/4" />
            @else
                <x-item__buttons :item="$item" class="h-[2.2em]" />
            @endif
            @else
        
            <p class="text-[1.28em] font-bold">Количество:{{ session('temporary_cart.items.' . $item->id . '.amount') }}
            </p>
            @endif
        </div>
    @endforeach
@else
    <h1>Товары не были найдены</h1>
@endif
