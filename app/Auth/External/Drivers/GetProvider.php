<?php

namespace App\Auth\External\Drivers;

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