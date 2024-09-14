<?php

namespace App\Auth\External\Factories;

use App\Auth\External\Authenticators\AuthenticatorInterface;
use App\Auth\External\Authenticators\BarAuthenticator;
use App\Auth\External\Authenticators\BazAuthenticator;
use App\Auth\External\Authenticators\FooAuthenticator;

class AuthenticatorFactory
{
    /**
     * @param string $login
     * @return AuthenticatorInterface
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \InvalidArgumentException
     */
    public static function create(string $login): AuthenticatorInterface
    {
        if (str_starts_with($login, 'FOO_')) {
            return app()->make(FooAuthenticator::class);
        } elseif (str_starts_with($login, 'BAR_')) {
            return app()->make(BarAuthenticator::class);
        } elseif (str_starts_with($login, 'BAZ_')) {
            return app()->make(BazAuthenticator::class);
        }

        throw new \InvalidArgumentException('Invalid login prefix.');
    }
}