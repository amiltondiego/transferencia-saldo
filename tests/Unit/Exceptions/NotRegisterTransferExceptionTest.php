<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use App\Exceptions\NotRegisterTransferException;
use Illuminate\Http\JsonResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class NotRegisterTransferExceptionTest extends TestCase
{
    public function testValidateStatus()
    {
        $exception = new NotRegisterTransferException();
        $this->assertSame(JsonResponse::HTTP_BAD_REQUEST, $exception->status());
    }

    public function testValidateContentMessage()
    {
        $exception = new NotRegisterTransferException();
        $this->assertArrayHasKey('message', $exception->contentMessage());
        $this->assertSame('not register transfer.', $exception->contentMessage()['message']);
    }
}
