<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Banner;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category = null)

    {   
        // session()->flush();
        // Cache::flush();
        if ($category != null) {
            if ($category->parent_id == 0) {
                $category->load('children');
                $categoryForQuery = $category->children->pluck('id');
            } else {
                $category->load('parent');
                $categoryForQuery = $category->id;
            }
        } else {
            $categoryForQuery = [];
        }
        $items = Item::where('visibleInCatalog', '=', 1)
            ->withAvg('reviews', 'grade')
            ->withCount('reviews')
            ->with('images', function ($query) {
                $query->limit(1);
            })
            ->with('prices', function ($query) {
                $query->where('price_start_date', '<=', now()->toDateString())
                    ->where('price_end_date', '>=', now()->toDateString());
            })
            ->when($category, function (Builder $query) use ($categoryForQuery) {
                $query->whereIn('category_id', Arr::flatten([$categoryForQuery]));
            })->paginate(10);
        
        $banners = Cache::remember('banners', 86400, function () {
            $banners = Banner::where('date_start', '>=', Carbon::now()->startOfDay()->toDateTimeString())
                ->where('date_end', '<=', Carbon::now()->endOfDay()->toDateTimeString())->get();
            if ($banners->isEmpty()) {
                return [];
            }
            $bannerData = [];
            $singularItems = $banners->filter(function ($banner) {
                if ($banner->type == 'singular_item') {
                    return true;
                }
            });
            $ids = $singularItems->map(function ($banner) {
                $information = json_decode($banner->banner_information);
                return $information->item_id;
            });
            $singularItemsWithData = Item::whereIn('id', $ids)->with('images', function ($query) {
                $query->limit(1);
            })->with('prices', function ($query) {
                $query->where('price_start_date', '<=', now()->toDateString())
                    ->where('price_end_date', '>=', now()->toDateString());
            })->get();
            $singularItemsWithData = $singularItemsWithData->each(function ($item) {
                $item->type = 'singular_item';
            });
            array_push($bannerData, ...$singularItemsWithData);
            return $bannerData;
        });

        return response()->spa(
            view('catalog', compact('items', 'banners', 'category'))
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        // Carbon::setTestNow(now()->addYear());

        $item = Item::where('id', $request->item)->with('images')->with('prices', function ($query) {
            $query->where('prices.price_start_date', '<=', now()->toDateString());
        })->with('reviews', function ($query) {
            $query->limit(2);
            $query->with('user');
            $query->with('images');
            $query->with('user.image');
        })->withCount('reviews')->withAvg('reviews', 'grade')->get()->first();
        if ($item !== null) {
            if ($item->prices->last()) {
                if (!Carbon::now()->between($item->prices->last()->price_start_date, $item->prices->last()->price_end_date)) {
                    $item->itemUnavailable = true;
                }
            } else {
                $item->itemUnavailable = true;
            }
            if ($item->amount == 0) {
                $item->itemUnavailable = true;
            }
            return response()->spa(
                view('product', compact('item'))
            );
        } else {
            $items = Item::where('visibleInCatalog', '=', 1)->withAvg('reviews', 'grade')->withCount('reviews')->with('images', function ($query) {
                $query->limit(1);
            })->inRandomOrder()->limit(10)->get();
            return response()->spa(
                view('product', compact('item', 'items'))
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function search(Request $request)
    {
        $items = Item::limit(10)->with('images')->where('visibleInCatalog', '=', 1)->whereFullText('name', $request->search_query)->get();
        $categories = Category::limit(5)->whereFullText('name', $request->search_query)->get();
        $results = [];
        if ($items->isNotEmpty()) {
            $results['items'] = $items;
        }
        if ($categories->isNotEmpty()) {
            $results['categories'] = $categories;
        }

        return response()->spa(view('components.search__results', [
            'results' => $results
        ]));
    }
}
