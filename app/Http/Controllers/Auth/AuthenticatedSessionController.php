<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use PDO;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */


    public function create()
    {
        return response()->spa(view('auth.login'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        if ($request->session()->get('cart')) {
            $cartBeforeLogin = $request->session()->get('cart.items');
            $favoritesBeforeLogin = $request->session()->get('cart.favorites');
        }
        $request->authenticate();
        $request->session()->regenerate();
        if (isset($cartBeforeLogin)) {
            $request->session()->put('temporary_cart.items', $cartBeforeLogin);
        }
        $request->session()->forget('cart');
        $cartInformation = $this->getCartInformationIfExists();
        if ($cartInformation) {
            $request->session()->put('cart.items', unserialize($cartInformation->payload));
        }
        $favoriteInformation = $this->getFavoriteInformationIfExists();
        if ($favoriteInformation) {
            $favoritesFromDB = unserialize($favoriteInformation->payload);
            $request->session()->put('cart.favorites', $favoritesFromDB);
            if (isset($favoritesBeforeLogin)) {
                foreach ($favoritesBeforeLogin as $key => $value) {
                    if (!array_key_exists($key, session()->get('cart.favorites'))) {
                        session()->put('cart.favorites.' . $key, 1);
                    }
                }
                $favoriteInformation = serialize(session()->get('cart.favorites'));
                DB::table('favorites')->updateOrInsert(
                    [
                        'user_id' => Auth::id()

                    ],
                    [
                        'payload' => $favoriteInformation
                    ]
                );
            } else {
                $request->session()->put('cart.favorites', $favoritesFromDB);
            }
        } else {
            if (isset($favoritesBeforeLogin)) {
                $request->session()->put('cart.favorites', $favoritesBeforeLogin);
            }
        }
        return response()->spa(
            redirect()->intended('/catalog')
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->spa(redirect('/catalog'));
    }
    public function getCartInformationIfExists()
    {
        $cartInformation = DB::table('sessions')->where('user_id', '=', Auth::id())->first();
        if ($cartInformation !== null) {
            return $cartInformation;
        } else {
            return false;
        }
    }

    public function getFavoriteInformationIfExists()
    {
        $cartInformation = DB::table('favorites')->where('user_id', '=', Auth::id())->first();
        if ($cartInformation !== null) {
            return $cartInformation;
        } else {
            return false;
        }
    }
}
