<?php

namespace App\Movies\Services;

use App\Movies\Adapters\MovieServiceAdapterInterface;
use App\Movies\Exceptions\ServiceUnavailableException;
use Illuminate\Support\Facades\Cache;

class MovieService
{
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
     * Get all movie titles as an iterable.
     *
     * @return iterable
     * @throws ServiceUnavailableException
     */
    public function getTitles(): iterable
    {
        yield from $this->getTitlesWithRetry($this->fooService);
        yield from $this->getTitlesWithRetry($this->barService);
        yield from $this->getTitlesWithRetry($this->bazService);
    }

    /**
     * Get titles from a service with retry logic.
     *
     * @param MovieServiceAdapterInterface $service
     * @return iterable
     * @throws ServiceUnavailableException
     */
    protected function getTitlesWithRetry(MovieServiceAdapterInterface $service): iterable
    {
        $lastException = null;

        for ($i = 0; $i < $this->retryAttempts; $i++) {
            try {
                // Yield titles from the service
                yield from $service->getTitles();
                return; // Exit the function once successful
            } catch (ServiceUnavailableException $e) {
                $lastException = $e;
            }
        }

        // If retry attempts are exhausted, throw the last exception
        throw $lastException;
    }
}