<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MovieServiceFeatureTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_movies_not_empty()
    {
        $response = $this->get('/api/titles');

        // Decode the JSON response
        $data = $response->json();

        // Assert that the data is not empty
        $this->assertNotEmpty($data, "The response is empty");

        // Assert that the response status is OK (200)
        $response->assertStatus(200);
    }
}
