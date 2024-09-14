<?php

namespace App\Auth\External\Factories;

use App\Auth\External\Drivers\AuthenticatorDriverInterface;
use App\Auth\External\Drivers\BarDriver;
use App\Auth\External\Drivers\BazDriver;
use App\Auth\External\Drivers\FooDriver;

class DriverFactory
{
    /**
     * @param string $login
     * @return AuthenticatorDriverInterface
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \InvalidArgumentException
     */
    public function create(string $login): AuthenticatorDriverInterface
    {
        if (str_starts_with($login, 'FOO_')) {
            return app()->make(FooDriver::class);
        } elseif (str_starts_with($login, 'BAR_')) {
            return app()->make(BarDriver::class);
        } elseif (str_starts_with($login, 'BAZ_')) {
            return app()->make(BazDriver::class);
        }

        throw new \InvalidArgumentException('Invalid login prefix.');
    }
}