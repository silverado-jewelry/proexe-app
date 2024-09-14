<?php

namespace App\Movies\Adapters;

interface MovieServiceAdapterInterface
{
    /**
     * @return array
     * @throws \Throwable
     */
    public function getTitles(): array;
}