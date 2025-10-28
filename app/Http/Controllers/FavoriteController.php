<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    
    public function index(){
         $itemsInSession = session()->get('cart.favorites');
         if ($itemsInSession) {
            $itemsFromDB = Item::whereIn('id', array_keys($itemsInSession))->get();
            $itemsFromDB = $itemsFromDB->loadMissing([
                'images' => function ($query) {
                    $query->limit(1);
                },
                'prices' => function ($query) {
                    $query->where('price_start_date', '<=', now()->toDateString())
                        ->where('price_end_date', '>=', now()->toDateString());
                }
            ]);
            return response()->spa(
                view('favorites',[
                    'items' => $itemsFromDB
                ])
            );
        }
        else{
            return response()->spa(
                view('favorites',[
                    'items' => []
                ])
            );
        }

    }

    public function updateFavorite(Request $request)
    {
        $itemInSession = session()->get('cart.favorites.' . $request->item_id);
        if ($itemInSession) {
            session()->forget('cart.favorites.' . $request->item_id);
            $item = Item::where('id', $request->item_id)->get()->first();
            $available = $item->visibleInCatalog;
            if ($available == false) {
                session()->forget('cart.items.' . $request->item_id);
            }
            if (empty(session('cart.favorites'))) {
                    session()->forget('cart.favorites');
                    DB::table('favorites')->where('user_id', Auth::id())->delete();
            }
            return response()->spa(
                view('components.item__buttons', [
                    'disabled' => !$available,
                    'item' => $item
                ])
            );
        } else {
            session()->put('cart.favorites.' . $request->item_id, 1);
            $item = Item::where('id', $request->item_id)->get()->first();
            $available = $item->visibleInCatalog;
            if ($available == false) {
                session()->forget('cart.items.' . $request->item_id);
            }
            if (Auth::check()) {
                    $favoriteInformation = serialize(session()->get('cart.favorites'));
                    DB::table('favorites')->updateOrInsert(
                        [
                            'user_id' => Auth::id()

                        ],
                        [
                            'payload' => $favoriteInformation
                        ]
                    );
                }
            return response()->spa(
                view('components.item__buttons', [
                    'disabled' => !$available,
                    'item' => $item
                ])
            );
        }
    }
}
