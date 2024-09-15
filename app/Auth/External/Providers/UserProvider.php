<?php

namespace App\Auth\External\Providers;

use App\Auth\External\Factories\DriverFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserProvider
{

    public function authorize(string $token): ?array
    {
        try {
            $decodedToken = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            if (property_exists($decodedToken, 'login') && ($driver = DriverFactory::createStatic($decodedToken->login))) {
                return [
                    'id' => $decodedToken->login,
                    'token' => $token,
                    'provider' => $driver->getProvider(),
                ];
            }
        } catch (\Throwable $e) {
            return null;
        }

        return null;
    }
}