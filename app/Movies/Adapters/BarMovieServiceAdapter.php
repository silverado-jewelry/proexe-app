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
    public function getTitles(): iterable
    {
        try {
            $titles = $this->movieService->getTitles()['titles'];
        } catch (\Throwable $e) {
            throw new ServiceUnavailableException($e->getMessage());
        }

        foreach ($titles as $title) {
            yield $title['title'];
        }
    }
}