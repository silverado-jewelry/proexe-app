<?php

namespace App\Auth\External\Authenticators;

use App\Auth\External\Drivers\AuthenticatorDriverInterface;
use Firebase\JWT\JWT;

class Authenticator
{
    /** @var string|null */
    protected $token;

    /**
     * @param AuthenticatorDriverInterface $driver
     */
    public function __construct(
        protected AuthenticatorDriverInterface $driver
    ) {}

    /**
     * @param string $login
     * @param string $password
     * @return bool
     */
    public function authenticate(string $login, string $password): bool
    {
        if ($this->driver->authenticate($login, $password)) {
            $this->createToken($login);
            return true;
        }

        return false;
    }

    /**
     * @param string $login
     */
    protected function createToken(string $login): void
    {
        $payload = [
            'login' => $login,
            'provider' => $this->getProvider(),
        ];

        $this->token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->driver->getProvider();
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }
}