<?php

namespace App\Auth\External\Guards;

use App\Auth\External\Authenticators\Authenticator;
use App\Auth\External\Factories\DriverFactory;
use App\Auth\External\Providers\UserProvider;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class ExternalGuard implements Guard
{
    /** @var object|null */
    protected $user;

    /**
     * ExternalGuard constructor.
     *
     * @param DriverFactory $driverFactory
     */
    public function __construct(
        protected DriverFactory $driverFactory,
        protected Request $request
    ) {}

    /**
     * @inheritDoc
     */
    public function check()
    {
        return !is_null($this->user) || $this->attempt();
    }

    /**
     * @inheritDoc
     */
    public function guest()
    {
        return !$this->check();
    }

    /**
     * @inheritDoc
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @inheritDoc
     */
    public function id()
    {
        return $this->user?->id;
    }

    /**
     * @inheritDoc
     */
    public function validate(array $credentials = [])
    {
        $login = $credentials['login'] ?? null;
        $password = $credentials['password'] ?? null;

        if (!$login || !$password) {
            return false;
        }

        try {
            $authenticator = new Authenticator(
                $this->driverFactory->create($login)
            );
        } catch (\Throwable $e) {
            return false;
        }

        $authenticated = $authenticator->authenticate($login, $password);

        if ($authenticated) {
            $this->setUser((object) [
                'id' => $login,
                'token' => $authenticator->getToken(),
                'provider' => $authenticator->getProvider(),
            ]);
        }

        return $authenticated;
    }

    /**
     * @inheritDoc
     */
    protected function attempt()
    {
        $authorizationHeader = $this->request->header('Authorization');

        if ($authorizationHeader && preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
            $userProvider = new UserProvider();

            if (($user = $userProvider->authorize($matches[1] ?? '')) !== null) {
                $this->setUser((object) $user);
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function hasUser()
    {
        return !$this->guest();
    }

    /**
     * @inheritDoc
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}