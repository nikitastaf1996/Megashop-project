<?php

namespace App\Http\Controllers;

use App\Jobs\PopulateBanners;
use App\Models\Banner;
use App\Models\Item;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class CartController extends Controller
{


    public function index(Request $request)
    {
    
        


        if (session()->get('temporary_cart.items')) {
            $temporaryCartInformation = Item::whereIn('id', array_keys(session()->get('temporary_cart.items')))->with('images', function ($query) {
                $query->limit(1);
            })->with('prices', function ($query) {
                $query->where('price_start_date', '<=', now()->toDateString())
                    ->where('price_end_date', '>=', now()->toDateString());
            })->get();
        } else {
            $temporaryCartInformation = [];
        }
        $itemsInSession = session()->get('cart.items');
        if ($itemsInSession) {
            $itemsFromDB = Item::whereIn('id', array_keys($itemsInSession))->get();
            foreach ($itemsInSession as $key => $value) {
                $itemFromDB = $itemsFromDB->firstWhere('id', $key);
                if (!$itemFromDB->visibleInCatalog) {
                    session()->flash('cartnotification', 'Некоторые товары были удалены потому что они больше не доступны');
                    session()->forget('cart.items.' . $key);
                }
            }
        }

        if (session()->get('cart.items')) {
            $itemsFromDB = $itemsFromDB->loadMissing([
                'images' => function ($query) {
                    $query->limit(1);
                },
                'prices' => function ($query) {
                    $query->where('price_start_date', '<=', now()->toDateString())
                        ->where('price_end_date', '>=', now()->toDateString());
                }
            ]);
            $itemCount = 0;
            foreach ($itemsInSession as $item) {
                $itemCount += $item['amount'];
            }
            $itemSum = 0;
            foreach ($itemsFromDB as $item) {
                $itemSum += $item->prices[0]->price * $itemsInSession[$item->id]['amount'];
            }
            $cartInformation = [
                'itemCount' => $itemCount,
                'itemSum' => $itemSum
            ];

            return response()->spa(
                view('cart', [
                    'temporary_cart' => $temporaryCartInformation,
                    'items' => $itemsFromDB,
                    'cartInformation' => $cartInformation
                ])
            );
        } else {
            $items = Item::where('visibleInCatalog', '=', true)->withAvg('reviews', 'grade')->with('images', function ($query) {
                $query->limit(1);
            })->withCount('reviews')->with('prices', function ($query) {
                $query->where('price_start_date', '<=', now()->toDateString())
                    ->where('price_end_date', '>=', now()->toDateString());
            })->inRandomOrder()->limit(10)->get();
            return response()->spa(
                view('cart', [
                    'temporary_cart' => $temporaryCartInformation,
                    'items' => [],
                    'recomendations' => $items
                ])
            );
        }
    }


    public function removeTemp(Request $request)
    {
        session()->forget('temporary_cart');
        session()->flash('cartnotification', "Временная корзина была удалена");
        return response()->spa(redirect('/cart'));
    }
    public function mergeTemp(Request $request)
    {
        $temporaryCartInDB = Item::whereIn('id', array_keys(session()->get('temporary_cart.items')));
        foreach (session()->get('temporary_cart.items') as $key => $value) {
            if (session('cart.items')) {
                if (array_key_exists($key, session('cart.items'))) {
                    if ($value['amount'] + session('cart.items.' . $key . '.amount') > $temporaryCartInDB->firstWhere('id', $key)['amount']) {
                        session()->put('cart.items.' . $key . '.amount', $temporaryCartInDB->firstWhere('id', $key)->amount);
                    } else {
                        session()->put('cart.items.' . $key . '.amount', $value['amount'] + session('cart.items.' . $key . '.amount'));
                    }
                } else {
                    if ($value['amount'] > $temporaryCartInDB->firstWhere('id', $key)['amount']) {
                        session()->put('cart.items.' . $key . '.amount', $temporaryCartInDB->firstWhere('id', $key)->amount);
                    } else {
                        session()->put('cart.items.' . $key . '.amount', $value['amount']);
                    }
                }
            } else {
                if ($value['amount'] > $temporaryCartInDB->firstWhere('id', $key)['amount']) {
                    session()->put('cart.items.' . $key . '.amount', $temporaryCartInDB->firstWhere('id', $key)->amount);
                } else {
                    session()->put('cart.items.' . $key . '.amount', $value['amount']);
                }
            }
        }
        session()->forget('temporary_cart');

        if (Auth::check()) {
            $cartInformation = serialize(session()->get('cart.items'));
            DB::table('sessions')->updateOrInsert(
                [
                    'user_id' => Auth::id()

                ],
                [
                    'payload' => $cartInformation
                ]
            );
        }

        return response()->spa(redirect('/cart'));
    }

    public function addToCart(Request $request)
    {
        // Carbon::setTestNow(now()->addYear());
        $itemInSession = session()->get('cart.items.' . $request->item_id);
        $item = Item::where('id', $request->item_id)->get()->first();
        $available = $item->visibleInCatalog;
        if ($itemInSession) {
            $newAmount = $itemInSession['amount'] + 1;
            if ($newAmount > $item->amount) {
                $newAmount = $item->amount;
            }

            if ($available == false) {
                session()->forget('cart.items.' . $request->item_id);
                if (empty(session('cart.items'))) {
                    session()->forget('cart.items');
                    DB::table('sessions')->where('user_id', Auth::id())->delete();
                }
            } else {
                session()->put('cart.items.' . $request->item_id . '.amount', $newAmount);
                if (Auth::check()) {
                    $cartInformation = serialize(session()->get('cart.items'));
                    DB::table('sessions')->updateOrInsert(
                        [
                            'user_id' => Auth::id()

                        ],
                        [
                            'payload' => $cartInformation
                        ]
                    );
                }
            }

            return response()->spa(view('components.item__buttons', [
                'disabled' => !$available,
                'item' => $item
            ]));
        } else {
            if ($available) {
                session()->put('cart.items.' . $request->item_id, [
                    'amount' => 1
                ]);
                if (Auth::check()) {
                    $cartInformation = serialize(session()->get('cart.items'));
                    DB::table('sessions')->updateOrInsert(
                        [
                            'user_id' => Auth::id()

                        ],
                        [
                            'payload' => $cartInformation
                        ]
                    );
                }
            }

            return response()->spa(
                view(
                    'components.item__buttons',
                    [
                        'disabled' => !$available,
                        'item' => $item
                    ]
                )
            );
        }
    }

    public function removeFromCart(Request $request)
    {
        $itemInSession = session()->get('cart.items.' . $request->item_id);

        if ($itemInSession) {
            $newAmount = $itemInSession['amount'] - 1;
            if ($newAmount < 1) {
                session()->forget('cart.items.' . $request->item_id);

                $item = Item::where('id', $request->item_id)->get()->first();
                $available = $item->visibleInCatalog;
                if (empty(session('cart.items'))) {
                    session()->forget('cart.items');
                    DB::table('sessions')->where('user_id', Auth::id())->delete();
                }
                return response()->spa(
                    view('components.item__buttons', [
                        'disabled' => !$available,
                        'item' => $item
                    ])
                );
            } else {
                session()->put('cart.items.' . $request->item_id . '.amount', $newAmount);
                $item = Item::where('id', $request->item_id)->get()->first();
                $available = $item->visibleInCatalog;
                if ($available == false) {
                    session()->forget('cart.items.' . $request->item_id);
                }
                if (empty(session('cart.items'))) {
                    session()->forget('cart.items');
                    DB::table('sessions')->where('user_id', Auth::id())->delete();
                } else {
                    if (Auth::check()) {
                        $cartInformation = serialize(session()->get('cart.items'));
                        DB::table('sessions')->updateOrInsert(
                            [
                                'user_id' => Auth::id()

                            ],
                            [
                                'payload' => $cartInformation
                            ]
                        );
                    }
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
}
