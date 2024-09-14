<?php

namespace App\Providers;

use App\Movies\Adapters\BarMovieServiceAdapter;
use App\Movies\Adapters\BazMovieSeviceAdapter;
use App\Movies\Adapters\FooMovieServiceAdapter;
use App\Movies\Services\MovieService;
use Illuminate\Support\ServiceProvider;

class MoviesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MovieService::class, function ($app) {
            return new MovieService(
                fooService: $app->make(FooMovieServiceAdapter::class),
                barService: $app->make(BarMovieServiceAdapter::class),
                bazService: $app->make(BazMovieSeviceAdapter::class)
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
