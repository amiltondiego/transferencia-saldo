<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Types;

use App\Models\WalletType;
use Tests\TestCase;

/**
 * @internal
 */
class WalletTypeTest extends TestCase
{
    public function testValidateTypes()
    {
        $this->assertSame(1, WalletType::processing());
        $this->assertSame(2, WalletType::success());
        $this->assertSame(3, WalletType::fail());
    }

}
