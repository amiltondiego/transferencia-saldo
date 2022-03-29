<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use App\Exceptions\NotFoundPayerException;
use Illuminate\Http\JsonResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class NotFoundPayerExceptionTest extends TestCase
{
    public function testValidateStatus()
    {
        $exception = new NotFoundPayerException();
        $this->assertSame(JsonResponse::HTTP_BAD_REQUEST, $exception->status());
    }

    public function testValidateContentMessage()
    {
        $exception = new NotFoundPayerException();
        $this->assertArrayHasKey('message', $exception->contentMessage());
        $this->assertSame('not found payer.', $exception->contentMessage()['message']);
    }
}
