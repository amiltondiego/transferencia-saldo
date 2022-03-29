<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use App\Exceptions\NotFoundPayeeException;
use Illuminate\Http\JsonResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class NotFoundPayeeExceptionTest extends TestCase
{
    public function testValidateStatus()
    {
        $exception = new NotFoundPayeeException();
        $this->assertSame(JsonResponse::HTTP_BAD_REQUEST, $exception->status());
    }

    public function testValidateContentMessage()
    {
        $exception = new NotFoundPayeeException();
        $this->assertArrayHasKey('message', $exception->contentMessage());
        $this->assertSame('not found payee.', $exception->contentMessage()['message']);
    }
}
