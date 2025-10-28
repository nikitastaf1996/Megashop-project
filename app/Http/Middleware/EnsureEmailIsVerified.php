<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
    
        if($request->user()->hasVerifiedEmail()){
            return $next($request);
        }
        if(Carbon::now()->diffInDays($request->user()->email_verification_first_sent_at) > 3){
            if(session()->get('one-time-email-force')){
                return $next($request);
            }
            else{
                session()->put('one-time-email-force',true);
                return response()->spa(redirect('/force-verify-email'));
            }
        }
        else{
            return $next($request);
        }
        
    }
}
