<?php

declare(strict_types=1);

namespace Tests\Unit\Integrations;

use App\Integrations\ExternalNotifyDefault;
use App\Interfaces\UserInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * @internal
 */
class ExternalNotifyDefaultTest extends TestCase
{
    public function testConsultSuccess()
    {
        Http::fake([
            'o4d9z.mocklab.io/*' => Http::response([
                'message' => 'Success',
            ]),
        ]);
        $external = new ExternalNotifyDefault();

        /** @var UserInterface $user */
        $user = $this->mock(UserInterface::class);
        $this->assertTrue($external->consult($user));
    }

    public function testConsultFailAuthorization()
    {
        Http::fake([
            'o4d9z.mocklab.io/*' => Http::response([
                'message' => 'Fail',
            ]),
        ]);
        $external = new ExternalNotifyDefault();

        /** @var UserInterface $user */
        $user = $this->mock(UserInterface::class);
        $this->assertFalse($external->consult($user));
    }

    public function testConsultFailExceptionHttp()
    {
        Http::shouldReceive('get')->once()->andThrow(ConnectionException::class);
        Log::shouldReceive('debug')->once();
        $external = new ExternalNotifyDefault();

        /** @var UserInterface $user */
        $user = $this->mock(UserInterface::class);
        $this->assertFalse($external->consult($user));
    }
}
