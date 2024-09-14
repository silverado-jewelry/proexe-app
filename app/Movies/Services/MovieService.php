<?php

namespace App\Movies\Services;

use App\Movies\Adapters\MovieServiceAdapterInterface;
use App\Movies\Exceptions\ServiceUnavailableException;
use Illuminate\Support\Facades\Cache;

class MovieService
{
    /** @var string */
    protected $cacheKey = 'movies.titles';

    /** @var int */
    protected $retryAttempts = 3;

    /**
     * MovieService constructor.
     *
     * @param MovieServiceAdapterInterface $fooService
     * @param MovieServiceAdapterInterface $barService
     * @param MovieServiceAdapterInterface $bazService
     */
    public function __construct(
        protected MovieServiceAdapterInterface $fooService,
        protected MovieServiceAdapterInterface $barService,
        protected MovieServiceAdapterInterface $bazService
    ) {}

    /**
     * Get all movie titles.
     *
     * @return array
     * @throws ServiceUnavailableException
     */
    public function getTitles(): array
    {
        return Cache::remember($this->cacheKey, 300, function () {
            $titles = [];

            $titles = array_merge($titles, $this->retry(function() {
                return $this->fooService->getTitles();
            }, $this->retryAttempts));

            $titles = array_merge($titles, $this->retry(function() {
                return $this->barService->getTitles();
            }, $this->retryAttempts));

            $titles = array_merge($titles, $this->retry(function() {
                return $this->bazService->getTitles();
            }, $this->retryAttempts));

            return $titles;
        });
    }

    /**
     * Retry a callback a given number of times.
     *
     * @param callable $callback
     * @param int $times
     * @return mixed
     * @throws ServiceUnavailableException
     */
    protected function retry(callable $callback, int $attempts)
    {
        $lastException = null;

        for ($i = 0; $i < $attempts; $i++) {
            try {
                return $callback();
            } catch (ServiceUnavailableException $e) {
                $lastException = $e;
            }
        }

        throw $lastException;
    }
}