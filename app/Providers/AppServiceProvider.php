<?php

namespace App\Providers;

use Exception;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)->markdown('mail.verify-email', [
                'url' => $url
            ]);
        });
        Response::macro('spa',function($response = null){
            if(request()->expectsJson()){
                $payload = [];
                try{
                    $payload['redirect'] = $response->getTargetUrl();
                }
                catch(Exception $e){}
                
                try{
                    $payload['content'] = $response->content();
                }
                catch(Exception $e){}

                if($response instanceof \Illuminate\View\View){
                    $sections =  $response->renderSections();
                    if(array_key_exists('content',$sections)){
                        $payload['content'] = $sections['content'];
                    }
                    else{
                        $payload['content'] = $response->render();
                    }
                    if(array_key_exists('title',$sections)){
                        $payload['title'] = $sections['title'];
                    }
                    
                }

                return response()->json($payload);
            }
            return $response;
        });
    }
}
