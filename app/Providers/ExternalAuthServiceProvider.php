<?php

namespace App\Providers;

use App\Auth\External\Drivers\BarDriver;
use App\Auth\External\Drivers\BazDriver;
use App\Auth\External\Drivers\FooDriver;
use App\Auth\External\Factories\DriverFactory;
use App\Auth\External\Guards\ExternalGuard;
use External\Bar\Auth\LoginService as ExternalBarAuthenticator;
use External\Baz\Auth\Authenticator as ExternalBazAuthenticator;
use External\Foo\Auth\AuthWS as ExternalFooAuthenticator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class ExternalAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FooDriver::class, fn() => new FooDriver(new ExternalFooAuthenticator()));
        $this->app->bind(BarDriver::class, fn() => new BarDriver(new ExternalBarAuthenticator()));
        $this->app->bind(BazDriver::class, fn() => new BazDriver(new ExternalBazAuthenticator()));
        $this->app->bind(ExternalGuard::class, fn($app) => new ExternalGuard(new DriverFactory(), $app['request']));
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Auth::extend('external', function ($app, $name, array $config) {
            return $app->make(ExternalGuard::class);
        });
    }
}
