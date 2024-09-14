<?php

namespace App\Providers;

use App\Auth\External\Authenticators\BarAuthenticator;
use App\Auth\External\Authenticators\BazAuthenticator;
use App\Auth\External\Authenticators\FooAuthenticator;
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
        $this->app->bind(FooAuthenticator::class, fn() => new FooAuthenticator(new ExternalFooAuthenticator()));
        $this->app->bind(BarAuthenticator::class, fn() => new BarAuthenticator(new ExternalBarAuthenticator()));
        $this->app->bind(BazAuthenticator::class, fn() => new BazAuthenticator(new ExternalBazAuthenticator()));
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Auth::extend('external', function ($app, $name, array $config) {
            return new ExternalGuard();
        });
    }
}
