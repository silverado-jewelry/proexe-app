<?php

namespace App\Auth\External\Authenticators;

interface AuthenticatorInterface
{
    /**
     * @param string $login
     * @param string $password
     * @return bool
     */
    public function authenticate(string $login, string $password): bool;

    /**
     * @return string
     */
    public function getProvider(): string;
}