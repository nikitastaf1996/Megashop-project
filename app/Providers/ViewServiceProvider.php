<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('header', function ($view) {
            // Cache::forget('categories');
            $categories =  Cache::remember('categories', 86400, function () {
                return  Category::where('parent_id', 0)->with('children')->get();
            });
            $view->with(compact('categories'));
        });
    }
}
