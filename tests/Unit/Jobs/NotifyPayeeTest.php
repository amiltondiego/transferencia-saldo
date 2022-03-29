<?php

declare(strict_types=1);

namespace Tests\Unit\Integrations;

use App\Interfaces\ExternalNotifyInterface;
use App\Interfaces\UserInterface;
use App\Jobs\NotifyPayee;
use Illuminate\Support\Facades\Queue;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * @internal
 */
class NotifyPayeeTest extends TestCase
{
    public function testHandleSuccess()
    {
        /** @var UserInterface $user */
        $user = $this->mock(UserInterface::class);

        /** @var ExternalNotifyInterface $externalNotify */
        $externalNotify = $this->mock(ExternalNotifyInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('consult')->andReturn(true);
        });
        
        $job = new NotifyPayee($user);

        $job->handle($externalNotify);

    }

    public function testHandleFail()
    {
        /** @var UserInterface $user */
        $user = $this->mock(UserInterface::class);

        /** @var ExternalNotifyInterface $externalNotify */
        $externalNotify = $this->mock(ExternalNotifyInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('consult')->andReturn(false);
        });
        
        $job = new NotifyPayee($user);

        $job->handle($externalNotify);

    }

}
