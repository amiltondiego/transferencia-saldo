<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use App\Exceptions\NotAuthorizedTransferException;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * @internal
 */
class NotAuthorizedTransferExceptionTest extends TestCase
{
    public function testValidateStatus()
    {
        $exception = new NotAuthorizedTransferException();
        $this->assertSame(JsonResponse::HTTP_BAD_REQUEST, $exception->status());
    }

    public function testValidateContentMessage()
    {
        $exception = new NotAuthorizedTransferException();
        $this->assertArrayHasKey('message', $exception->contentMessage());
        $this->assertSame('not authorized transfer.', $exception->contentMessage()['message']);
    }
}
