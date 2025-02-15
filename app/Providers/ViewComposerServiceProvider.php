<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\DestinationController;

class ViewComposerServiceProvider extends ServiceProvider
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
        View::composer('user.components.footer', function ($view) {
            $destinationController = new DestinationController();
            $popularDestinations = $destinationController->getPopularDestinations();
            $view->with('popularDestinations', $popularDestinations);
        });
    }
}
