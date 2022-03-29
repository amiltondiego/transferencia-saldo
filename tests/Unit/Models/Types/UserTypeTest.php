<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Types;

use App\Models\UserType;
use Tests\TestCase;

/**
 * @internal
 */
class UserTypeTest extends TestCase
{
    public function testValidateTypes()
    {
        $this->assertSame(1, UserType::common());
        $this->assertSame(2, UserType::shopkeeper());
    }

}
