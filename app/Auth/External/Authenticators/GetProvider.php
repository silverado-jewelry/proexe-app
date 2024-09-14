<?php

namespace App\Auth\External\Authenticators;

trait GetProvider
{
    /**
     * @return string
     */
    public function getProvider(): string
    {
        return property_exists($this, 'provider') ? (string) $this->provider : '';
    }
}