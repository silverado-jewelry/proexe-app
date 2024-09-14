<?php

namespace App\Auth\External\Authenticators;

use External\Baz\Auth\Authenticator;
use External\Baz\Auth\Responses\Success;

class BazAuthenticator implements AuthenticatorInterface
{
    use GetProvider;

    /** @var string */
    protected string $provider = 'baz';

    /**
     * @param Authenticator $authService
     */
    public function __construct(
        protected Authenticator $authService,
    ) {}

    /**
     * @inheritDoc
     */
    public function authenticate(string $login, string $password): bool
    {
        $response = $this->authService->auth($login, $password);

        return $response instanceof Success;
    }
}