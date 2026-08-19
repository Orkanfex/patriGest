<?php

namespace App\Providers;

use App\Models\Environment;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
// use Illuminate\View\View;

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
        //
        Paginator::useBootstrapFive();

        // View::composer('parts.sidebar', function($view){
        //     $user = Auth::user();

        //     $environments = $user->environments;
            
        //     return $view->with('environments', $environments);
        // });
    }
}
