<?php


namespace Beaubus\FaviconsForLaravel;

use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Loading the views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'favicons');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}