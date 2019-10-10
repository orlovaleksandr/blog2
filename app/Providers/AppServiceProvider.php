<?php

namespace App\Providers;

use App\Category;
use App\Post;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('pages._sidebar', function ($view){
           $view->with('popular',  Post::orderBy('views', 'desc')->take(3)->get());
           $view->with('featured',  Post::where('is_featured', '==', '1')->take(3)->get());
           $view->with('recent',  Post::orderBy('date', 'desc')->take(4)->get());
           $view->with('categories',  Category::all());
        });
    }
}
