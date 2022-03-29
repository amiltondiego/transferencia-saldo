<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use App\Exceptions\WithoutBalanceException;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * @internal
 */
class WithoutBalanceExceptionTest extends TestCase
{
    public function testValidateStatus()
    {
        $exception = new WithoutBalanceException();
        $this->assertSame(JsonResponse::HTTP_BAD_REQUEST, $exception->status());
    }

    public function testValidateContentMessage()
    {
        $exception = new WithoutBalanceException();
        $this->assertArrayHasKey('message', $exception->contentMessage());
        $this->assertSame('user without balance.', $exception->contentMessage()['message']);
    }
}
