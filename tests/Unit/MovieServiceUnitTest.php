<?php

namespace Tests\Unit;

use App\Movies\Adapters\MovieServiceAdapterInterface;
use App\Movies\Exceptions\ServiceUnavailableException;
use App\Movies\Services\MovieService;
use Mockery;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class MovieServiceUnitTest extends TestCase
{
    protected $fooServiceMock;
    protected $barServiceMock;
    protected $bazServiceMock;
    protected $movieService;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::shouldReceive('remember')
            ->andReturnUsing(function ($key, $time, $callback) {
                return $callback();  // Directly call the callback, no cache involved
            });

        $this->fooServiceMock = Mockery::spy(MovieServiceAdapterInterface::class);
        $this->barServiceMock = Mockery::spy(MovieServiceAdapterInterface::class);
        $this->bazServiceMock = Mockery::spy(MovieServiceAdapterInterface::class);

        $this->movieService = new MovieService(
            $this->fooServiceMock,
            $this->barServiceMock,
            $this->bazServiceMock
        );
    }

    public function test_get_titles_successful()
    {
        $this->fooServiceMock->shouldReceive('getTitles')->andReturn([
            'Attack of the 50 Foot Woman',
            'The Fish That Saved Pittsburgh'
        ]);

        $this->barServiceMock->shouldReceive('getTitles')->andReturn([
            'Star Wars: Episode IV - A New Hope',
            'The Devil and Miss Jones'
        ]);

        $this->bazServiceMock->shouldReceive('getTitles')->andReturn([
            'The Kentucky Fried Movie',
            'Dog Day Afternoon'
        ]);

        $titles = $this->movieService->getTitles();

        $this->assertEquals([
            'Attack of the 50 Foot Woman',
            'The Fish That Saved Pittsburgh',
            'Star Wars: Episode IV - A New Hope',
            'The Devil and Miss Jones',
            'The Kentucky Fried Movie',
            'Dog Day Afternoon',
        ], $titles);

        $this->fooServiceMock->shouldHaveReceived('getTitles')->once();
        $this->barServiceMock->shouldHaveReceived('getTitles')->once();
        $this->bazServiceMock->shouldHaveReceived('getTitles')->once();
    }

    public function test_get_titles_with_service_unavailable_exception()
    {
        $this->fooServiceMock->shouldReceive('getTitles')
            ->times(3)
            ->andThrow(new ServiceUnavailableException('Foo Service unavailable'));

        $this->barServiceMock->shouldReceive('getTitles')->andReturn([
            'Star Wars: Episode IV - A New Hope',
            'The Devil and Miss Jones'
        ]);

        $this->bazServiceMock->shouldReceive('getTitles')->andReturn([
            'The Kentucky Fried Movie',
            'Dog Day Afternoon'
        ]);

        $this->expectException(ServiceUnavailableException::class);

        $this->movieService->getTitles();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}