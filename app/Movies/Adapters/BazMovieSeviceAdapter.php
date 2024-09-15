<?php

namespace App\Movies\Adapters;

use App\Movies\Exceptions\ServiceUnavailableException;
use External\Baz\Movies\MovieService as ExternalMovieService;
use Illuminate\Support\Facades\Cache;

class BazMovieSeviceAdapter implements MovieServiceAdapterInterface
{
    /**
     * BazMovieSeviceAdapter constructor.
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
            $titles = Cache::remember('movies.baz.titles', 60, function () {
                return $this->movieService->getTitles()['titles'];
            });
        } catch (\Throwable $e) {
            throw new ServiceUnavailableException($e->getMessage());
        }

        foreach ($titles as $title) {
            yield $title;
        }
    }
}