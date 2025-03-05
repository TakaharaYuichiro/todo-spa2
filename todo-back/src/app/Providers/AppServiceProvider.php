<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\AuthException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->singleton(Auth::class, function ($app) {
        $factory = (new Factory)
            ->withServiceAccount(config('firebase.credentials'));

        return $factory->createAuth();
    });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

}
