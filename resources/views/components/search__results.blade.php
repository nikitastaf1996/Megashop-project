
@if(!empty($results['categories']))
@if(count($results['categories']) > 0)
@foreach($results['categories'] as $category)
<div href="{{url('/catalog',[$category->id])}}" class="flex cursor-pointer text-black bg-slate-400 items-center h-[70px] text-3xl hover:bg-[rgb(254,245,218)]">
    <p>{{$category->name}}</p>
</div>
@endforeach
@endif
@endif
@if(!empty($results['items']))
@if(count($results['items']) > 0)
@foreach($results['items'] as $item)
<div href="{{url('/product',[$item->id])}}" class="flex cursor-pointer text-black items-center h-[70px] text-base hover:bg-[rgb(254,245,218)]">
    <image class="w-8 h-[50px] mr-3" loading='lazy' src="{{asset('storage/'.$item->images->first()->image_path)}}">
        <p>{{$item->name}}</p>
</div>
@endforeach
@endif
@endif