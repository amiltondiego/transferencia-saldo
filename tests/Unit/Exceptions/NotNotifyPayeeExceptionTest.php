<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use App\Exceptions\NotNotifyPayeeException;
use Tests\TestCase;

/**
 * @internal
 */
class NotNotifyPayeeExceptionTest extends TestCase
{
    public function testValidateContentMessage()
    {
        $exception = new NotNotifyPayeeException();
        $this->assertIsString($exception->contentMessage());
        $this->assertSame('not notify payee.', $exception->contentMessage());
        $this->assertSame('not notify payee.', $exception->getMessage());
    }
}
