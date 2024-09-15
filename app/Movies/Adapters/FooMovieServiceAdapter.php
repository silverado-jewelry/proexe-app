<?php

namespace App\Movies\Adapters;

use App\Movies\Exceptions\ServiceUnavailableException;
use External\Foo\Movies\MovieService as ExternalMovieService;
use Illuminate\Support\Facades\Cache;

class FooMovieServiceAdapter implements MovieServiceAdapterInterface
{
    /**
     * FooMovieServiceAdapter constructor.
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
            $titles = Cache::remember('movies.foo.titles', 60, function () {
                return $this->movieService->getTitles();
            });
        } catch (\Throwable $e) {
            throw new ServiceUnavailableException($e->getMessage());
        }

        foreach ($titles as $title) {
            yield $title;
        }
    }
}