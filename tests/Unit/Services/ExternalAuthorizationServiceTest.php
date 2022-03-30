<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Interfaces\ExternalAuthorizationInterface;
use App\Interfaces\UserInterface;
use App\Services\ExternalAuthorizationService;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * @internal
 */
class ExternalAuthorizationServiceTest extends TestCase
{
    public function testHasAuthorizationTrue()
    {
        /** @var UserInterface $payer */
        $payer = $this->mock(UserInterface::class);

        /** @var ExternalAuthorizationInterface $externalAuthorization */
        $externalAuthorization = $this->mock(ExternalAuthorizationInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('consult')->andReturnTrue();
        });
        $service = new ExternalAuthorizationService($externalAuthorization);

        $this->assertTrue($service->hasAuthorization($payer));
    }

    public function testHasAuthorizationFalse()
    {
        /** @var UserInterface $payer */
        $payer = $this->mock(UserInterface::class);

        /** @var ExternalAuthorizationInterface $externalAuthorization */
        $externalAuthorization = $this->mock(ExternalAuthorizationInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('consult')->andReturnFalse();
        });
        $service = new ExternalAuthorizationService($externalAuthorization);

        $this->assertFalse($service->hasAuthorization($payer));
    }

}
