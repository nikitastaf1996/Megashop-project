<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendAccountVerificationEmail;
use App\Mail\SendAccountEmailConfirmation;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    public function index(Request $request){
        return $request->user()->hasVerifiedEmail()
                    ? request()->spa(redirect('/catalog'))
                    : request()->spa(view('auth.verify-email'));
    }
    public function forceEmailVerification(Request $request){
        return request()->spa(view('auth.force-email-verification'));
    }
    public function store(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return request()->spa(redirect('/catalog'));
        }

        SendAccountVerificationEmail::dispatch($request->user());
        
        return request()->spa(redirect('/verify-email'));
    }

    
}
