<?php

namespace Tests\Unit;

use App\Auth\External\Authenticators\Authenticator;
use App\Auth\External\Drivers\AuthenticatorDriverInterface;
use App\Auth\External\Factories\DriverFactory;
use App\Auth\External\Guards\ExternalGuard;
use Tests\TestCase;
use Mockery;

class ExternalAuthUnitTest extends TestCase
{
    protected $guard;
    protected $driverFactoryMock;
    protected $driverMock;
    protected $authenticator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->driverFactoryMock = $this->createMock(DriverFactory::class);

        $this->driverMock = $this->createMock(AuthenticatorDriverInterface::class);

        $this->authenticator = $this->createMock(Authenticator::class);

        $this->guard = new ExternalGuard($this->driverFactoryMock);
    }

    public function test_validate_with_valid_credentials()
    {
        $login = 'BAR_123';
        $password = 'foo-bar-baz';

        $this->driverFactoryMock->expects($this->once())
            ->method('create')
            ->with($login)
            ->willReturn($this->driverMock);

        $this->driverMock->expects($this->once())
            ->method('authenticate')
            ->with($login, $password)
            ->willReturn(true);

        $this->driverMock->expects($this->once())
            ->method('getProvider')
            ->willReturn('bar');

        $result = $this->guard->validate(['login' => $login, 'password' => $password]);

        $this->assertTrue($result, "Validate method did not return true with valid credentials");

        $this->assertTrue($this->guard->check(), "User was not set correctly");
        $this->assertTrue($this->guard->hasUser(), "User was not set correctly");
        $this->assertEquals($this->guard->user()->id, $login, "User ID was not set correctly");
        $this->assertEquals($this->guard->id(), $login, "User ID was not set correctly");
        $this->assertNotNull($this->guard->user()->token, "Token was not generated correctly");
    }

    public function test_validate_with_invalid_password()
    {
        $login = 'BAR_123';
        $password = 'invalid-password';

        $this->driverFactoryMock->expects($this->once())
            ->method('create')
            ->with($login)
            ->willReturn($this->driverMock);

        $this->driverMock->expects($this->once())
            ->method('authenticate')
            ->with($login, $password)
            ->willReturn(false);

        $result = $this->guard->validate(['login' => $login, 'password' => $password]);

        $this->assertFalse($result);
        $this->assertNull($this->guard->user());
    }

    public function test_validate_with_invalid_login()
    {
        $login = 'BAD_123';
        $password = 'foo-bar-baz';

        $this->driverFactoryMock->expects($this->once())
            ->method('create')
            ->with($login)
            ->willReturn($this->driverMock);

        $this->driverMock->expects($this->once())
            ->method('authenticate')
            ->with($login, $password)
            ->willReturn(false);

        $result = $this->guard->validate(['login' => $login, 'password' => $password]);

        $this->assertFalse($result);
        $this->assertNull($this->guard->user());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}