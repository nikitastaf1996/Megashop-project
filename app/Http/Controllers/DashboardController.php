<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function indexOrders(Request $request)
    {
        $orders = Auth::user()->orders->loadMissing('order_items', 'order_items.item')
            ->loadMissing(
                ['order_items.item.images' => function ($query) {
                    $query->limit(1);
                }]
            );
        return response()->spa(
            view('dashboard.dashboard', [
                'orders' => $orders
            ])
        );
    }

    public function indexSettings($page = 'main')
    {
        return response()->spa(view('dashboard.settings_' . $page, [
            'user' => Auth::user()
        ]));
    }
    public function updateSettings($page)
    {

        switch ($page) {
            case 'deleteAccount':
                $user = User::find(Auth::user()->id);
                $user->delete();
                Auth::guard('web')->logout();
                request()->session()->invalidate();
                request()->session()->regenerateToken();
                return response()->spa(redirect('/catalog'));
                break;
            case 'promotional':
                $user = User::find(Auth::user()->id);
                $user->promotional = !$user->promotional;
                $user->save();
                return response()->spa(redirect('/user/settings/'));
                break;
            default:
                return response()->spa(redirect('/user/settings/'));
                break;
        }
    }
}
