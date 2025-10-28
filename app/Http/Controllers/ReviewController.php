<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    //

    public function index(Request $request)
    {
        $item = Item::where('id', $request->item)->with('images', function ($query) {
            $query->limit(1);
        })->with('prices', function ($query) {
            $query->where('prices.price_start_date', '<=', now()->toDateString());
            $query->where('prices.price_end_date', '>=', now()->toDateString());
        })->withCount('reviews')->withAvg('reviews', 'grade')->get()->first();
        $reviews = Review::where('item_id', $request->item)->with('images', 'user', 'user.image')->paginate(10);
        return response()->spa(view('reviews', compact('item', 'reviews')));
    }
}
