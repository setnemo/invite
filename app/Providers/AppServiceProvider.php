<?php

namespace App\Providers;

use App\Services\BlueSky;
use App\Services\CodeService;
use GuzzleHttp\Client;
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
        $this->app->singleton(BlueSky::class, function () {
            return new BlueSky(new Client());
        });
        $this->app->singleton(CodeService::class, function () {
            return new CodeService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \URL::forceRootUrl(\Config::get('app.url'));
        if (\Str::contains(\Config::get('app.url'), 'https://')) {
            \URL::forceScheme('https');
        }
    }
}
