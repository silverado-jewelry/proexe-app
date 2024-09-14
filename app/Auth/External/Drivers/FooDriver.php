<?php

namespace App\Auth\External\Drivers;

use External\Foo\Auth\AuthWS as ExternalAuthenticator;

class FooDriver implements AuthenticatorDriverInterface
{
    use GetProvider;

    /** @var string */
    protected string $provider = 'foo';

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
        try {
            $this->authService->authenticate($login, $password);
        } catch (\Throwable $e) {
            return false;
        }

        return true;
    }
}