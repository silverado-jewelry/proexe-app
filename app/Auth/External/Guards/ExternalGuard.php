<?php

namespace App\Auth\External\Guards;

use App\Auth\External\Authenticators\Authenticator;
use App\Auth\External\Factories\DriverFactory;
use Illuminate\Contracts\Auth\Guard;

class ExternalGuard implements Guard
{
    /** @var object|null */
    protected $user;

    /**
     * @inheritDoc
     */
    public function check()
    {
        return !is_null($this->user);
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
                DriverFactory::create($login)
            );
        } catch (\Throwable $e) {
            return false;
        }

        $authenticated = $authenticator->authenticate($login, $password);

        if ($authenticated) {
            $this->setUser((object) [
                'id' => $login,
                'token' => $authenticator->getToken(),
            ]);
        }

        return $authenticated;
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