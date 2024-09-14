<?php

namespace App\Movies\Adapters;

use App\Movies\Exceptions\ServiceUnavailableException;
use External\Bar\Movies\MovieService as ExternalMovieService;

class BarMovieServiceAdapter implements MovieServiceAdapterInterface
{
    /**
     * BarMovieServiceAdapter constructor.
     *
     * @param ExternalMovieService $movieService
     */
    public function __construct(
        protected ExternalMovieService $movieService
    ) {}

    /**
     * @inheritDoc
     */
    public function getTitles(): array
    {
        try {
            $titles = $this->movieService->getTitles()['titles'];
        } catch (\Throwable $e) {
            throw new ServiceUnavailableException($e->getMessage());
        }

        return array_map(fn($movie) => $movie['title'], $titles);
    }
}