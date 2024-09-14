<?php

namespace App\Auth\External\Decorators;

use App\Auth\External\Authenticators\AuthenticatorInterface;
use Firebase\JWT\JWT;

class AuthenticatorDecorator
{
    /** @var string|null */
    protected $token;

    /**
     * @param AuthenticatorInterface $authenticator
     */
    public function __construct(
        protected AuthenticatorInterface $authenticator
    ) {}

    /**
     * @param string $login
     * @param string $password
     * @return bool
     */
    public function authenticate(string $login, string $password): bool
    {
        if ($this->authenticator->authenticate($login, $password)) {
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
            'provider' => $this->authenticator->getProvider(),
        ];

        $this->token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }
}