<?php

namespace App\Auth\External\Authenticators;

use External\Bar\Auth\LoginService;

class BarAuthenticator implements AuthenticatorInterface
{
    use GetProvider;

    /** @var string */
    protected string $provider = 'bar';

    /**
     * @param LoginService $authService
     */
    public function __construct(
        protected LoginService $authService,
    ) {}

    /**
     * @inheritDoc
     */
    public function authenticate(string $login, string $password): bool
    {
        return $this->authService->login($login, $password);
    }
}