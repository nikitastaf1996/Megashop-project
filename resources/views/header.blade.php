<header class="flex flex-row flex-wrap justify-between sm:justify-start sm:items-center mb-3 sm:gap-3">
    <a class="text-3xl w-full text-center sm:w-auto font-bold uppercase hover:bg-brand hover:text-white" href="{{ url('/catalog') }}">МегаШоп</a>
    <div data-component='category' class="categories_open cursor-pointer flex  justify-center items-center ml-5 sm:ml-0 w-10 h-10 bg-brand relative">
        <i class="categories_open fa-solid fa-bars"></i>
        <i class="categories_close fa-solid fa-x !hidden" style="color: #ff0000;"></i>
        <div class="category_list place-content-evenly	 overflow-scroll overscroll-contain	left-[-20px] top-[-36px] hidden absolute z-50 flex flex-col sm:flex-wrap w-screen h-screen sm:h-[80vh] bg-white sm:left-[-200px] sm:top-[40px]">
            <div class="category_switcher categories_open border-solid border-1 border-black sm:hidden fixed w-10 h-10 top-3 right-0 flex flex-col flex-wrap">
                <i class="fa-solid fa-x categories_close" style="color: #ff0000;"></i>
            </div>
            <div class="category_switcher categories_back sm:hidden fixed w-10 h-10 top-3 left-0 flex flex-col flex-wrap">
                <i class="categories_back fa-solid fa-arrow-left"></i>
            </div>
            @foreach ($categories as $parentCategory)
            <div class="w-full sm:min-w-[120px] sm:w-auto text-left">
                <a data-level="0" data-parent-id="{{ $parentCategory->id }}" class="category_switcher mt-3 mx-10 text-lg border-solid border-b-2 border-brand sm:border-none sm:m-0 sm:text-sm block font-bold align-top hover:text-brand flex " href="{{ url('catalog', [$parentCategory->id]) }}#page_name">
                    {{ $parentCategory->name }}
                </a>
                @foreach ($parentCategory->children as $childCategory)
                <a data-level="1" data-parent-id="{{ $parentCategory->id }}" class="category_switcher hidden mt-3 text-lg border-solid border-b-2 border-brand sm:border-none sm:m-0 sm:block text-2xl sm:text-xs block align-top hover:text-brand" href="{{ url('catalog', [$childCategory->id]) }}#page_name">
                    {{ $childCategory->name }}
                </a>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>
    <div data-component="search" class="lg:w-[370px] overscroll-contain lg:order-none order-1 w-full h-10 flex relative">
        <div class="search-back hidden sm:hidden flex items-center justify-center w-1/5 z-30 text-lg border-2 border-brand bg-brand hover:bg-[#ffea94] hover:border-[#ffea94] "><i class="search-back fa-solid fa-arrow-left"></i></div>
        <input placeholder="Поиск" type="text" class="search-input text-lg text-[#222] placeholder:text-[rgb(140,140,140)] leading-relaxed z-30 flex-1 p-1 outline-none border-2 border-brand
         peer pl-4 pr-9"></input>
        <button class="hidden search-action sm:block w-1/5 z-30 font-bold text-lg border-2 border-brand bg-brand hover:bg-[#ffea94] hover:border-[#ffea94] ">Найти</button>
        <button class="sm:hidden search-action w-1/5 z-30 text-lg border-2 border-brand bg-brand hover:bg-[#ffea94] hover:border-[#ffea94] "><i class="fa-solid fa-magnifying-glass"></i></button>
        <div class="absolute z-30 hidden w-full bg-white h-[350px] overscroll-contain overflow-scroll search-results top-10 ">
        </div>
        {{-- <div class="absolute overscroll-contain top-[-39px] left-[-266px] z-20 hidden w-screen h-screen  bg-gray-600/40 peer-focus:block"></div> --}}
    </div>
    <a href="{{ url('/favorite') }}" class="flex w-10 h-10">
        <i class="fa-solid fa-heart m-auto" style="color: #ff0000;"></i>
    </a>
    <a href="{{ url('/user') }}" class="flex w-10 h-10">
        <p class="m-auto"><i class="fa-solid fa-user m-auto" style="color: #0062ff;"></i></p>
    </a>
    <a href="{{ url('/cart') }}" class="flex mr-5 sm:mr-0 w-10 h-10">
        <i class="fa-solid fa-cart-shopping m-auto"></i>
    </a>
    
</header>
