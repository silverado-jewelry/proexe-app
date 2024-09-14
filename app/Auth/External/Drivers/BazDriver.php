<?php

namespace App\Auth\External\Drivers;

use External\Baz\Auth\Authenticator as ExternalAuthenticator;
use External\Baz\Auth\Responses\Success;

class BazDriver implements AuthenticatorDriverInterface
{
    use GetProvider;

    /** @var string */
    protected string $provider = 'baz';

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
        $response = $this->authService->auth($login, $password);

        return $response instanceof Success;
    }
}