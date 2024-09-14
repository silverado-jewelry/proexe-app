<?php

namespace App\Auth\External\Authenticators;

use External\Foo\Auth\AuthWS;

class FooAuthenticator implements AuthenticatorInterface
{
    use GetProvider;

    /** @var string */
    protected string $provider = 'foo';

    /**
     * @param AuthWS $authService
     */
    public function __construct(
        protected AuthWS $authService,
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