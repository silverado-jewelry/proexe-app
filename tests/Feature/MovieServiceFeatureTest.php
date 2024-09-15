<?php

namespace Tests\Feature;

use Tests\TestCase;

class MovieServiceFeatureTest extends TestCase
{
    public function test_movies_not_empty()
    {
        $response = $this->get('/api/titles');

        $response->assertStatus(200);

        $content = $response->streamedContent();

        $data = json_decode($content, true);

        $this->assertNotEmpty($data, "The streamed response is empty");

        $this->assertIsArray($data, "The streamed response is not an array");
        $this->assertNotEmpty($data[0], "The first movie title is empty");
    }
}