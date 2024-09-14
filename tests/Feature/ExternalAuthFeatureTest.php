<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExternalAuthFeatureTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_validate_with_valid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'login' => 'FOO_123',
            'password' => 'foo-bar-baz',
        ]);

        $response->assertStatus(200);

        $response = $this->postJson('/api/login', [
            'login' => 'BAR_123',
            'password' => 'foo-bar-baz',
        ]);

        $response->assertStatus(200);

        $response = $this->postJson('/api/login', [
            'login' => 'BAZ_123',
            'password' => 'foo-bar-baz',
        ]);

        $response->assertStatus(200);
    }

    public function test_validate_with_invalid_password()
    {
        $response = $this->postJson('/api/login', [
            'login' => 'FOO_123',
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(401);

        $response = $this->postJson('/api/login', [
            'login' => 'BAR_123',
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(401);

        $response = $this->postJson('/api/login', [
            'login' => 'BAZ_123',
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(401);
    }

    public function test_validate_with_invalid_login()
    {
        $response = $this->postJson('/api/login', [
            'login' => 'BAD_123',
            'password' => 'foo-bar-baz',
        ]);

        $response->assertStatus(401);
    }

    public function test_validate_with_empty_credentials()
    {
        $response = $this->postJson('/api/login');

        $response->assertStatus(401);
    }
}
