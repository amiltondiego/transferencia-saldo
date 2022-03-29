<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use App\Exceptions\NotCantPayerException;
use Illuminate\Http\JsonResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class NotCantPayerExceptionTest extends TestCase
{
    public function testValidateStatus()
    {
        $exception = new NotCantPayerException();
        $this->assertSame(JsonResponse::HTTP_BAD_REQUEST, $exception->status());
    }

    public function testValidateContentMessage()
    {
        $exception = new NotCantPayerException();
        $this->assertArrayHasKey('message', $exception->contentMessage());
        $this->assertSame('not can\'t payer.', $exception->contentMessage()['message']);
    }
}
