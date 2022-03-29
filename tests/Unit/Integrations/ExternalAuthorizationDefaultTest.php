<?php

declare(strict_types=1);

namespace Tests\Unit\Integrations;

use App\Integrations\ExternalAuthorizationDefault;
use App\Interfaces\UserInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * @internal
 */
class ExternalAuthorizationDefaultTest extends TestCase
{
    public function testConsultSuccess()
    {
        Http::fake([
            'run.mocky.io/*' => Http::response([
                'message' => 'Autorizado',
            ]),
        ]);
        $external = new ExternalAuthorizationDefault();

        /** @var UserInterface $user */
        $user = $this->mock(UserInterface::class);
        $this->assertTrue($external->consult($user));
    }

    public function testConsultFailAuthorization()
    {
        Http::fake([
            'run.mocky.io/*' => Http::response([
                'message' => 'Não Autorizado',
            ]),
        ]);
        $external = new ExternalAuthorizationDefault();

        /** @var UserInterface $user */
        $user = $this->mock(UserInterface::class);
        $this->assertFalse($external->consult($user));
    }

    public function testConsultFailExceptionHttp()
    {
        Http::shouldReceive('get')->once()->andThrow(ConnectionException::class);
        Log::shouldReceive('debug')->once();
        $external = new ExternalAuthorizationDefault();

        /** @var UserInterface $user */
        $user = $this->mock(UserInterface::class);
        $this->assertFalse($external->consult($user));
    }
}
