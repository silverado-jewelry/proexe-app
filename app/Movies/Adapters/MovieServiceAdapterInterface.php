<?php

namespace App\Movies\Adapters;

interface MovieServiceAdapterInterface
{
    /**
     * @return iterable
     * @throws \Throwable
     */
    public function getTitles(): iterable;
}