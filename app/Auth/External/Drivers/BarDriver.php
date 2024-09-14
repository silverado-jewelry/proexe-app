<?php

namespace App\Auth\External\Drivers;

use External\Bar\Auth\LoginService as ExternalAuthenticator;

class BarDriver implements AuthenticatorDriverInterface
{
    use GetProvider;

    /** @var string */
    protected string $provider = 'bar';

    /**
     * @param ExternalAuthenticator $authService
     */
    public function __construct(
        protected ExternalAuthenticator $authService,
    ) {}

    /**
     * @inheritDoc
     */
    public function authenticate(string $login, string $password): bool
    {
        return $this->authService->login($login, $password);
    }
}