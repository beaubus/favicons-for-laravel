<?php


namespace Beaubus\FaviconsForLaravel;

use Beaubus\FaviconsForLaravel\Commands\InstallBeaubusFavicon;
use Illuminate\Support\ServiceProvider;

class BeaubusFaviconsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Loading the routes
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        // from resources/favicons/default into storage/app/public/beaubus-favicons/
        $this->publishes([
            __DIR__.'/../resources/favicons/default' => storage_path('app/public/beaubus-favicons'),
            __DIR__.'/../resources/favicons/default/favicon.ico' => public_path('favicon.ico'),
        ], 'beaubus-favicons');

        // Loading the views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'favicons');

        $this->commands([InstallBeaubusFavicon::class]);
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