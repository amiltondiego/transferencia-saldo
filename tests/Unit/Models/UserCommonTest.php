<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Interfaces\UserInterface;
use App\Models\UserCommon;
use Tests\TestCase;

/**
 * @internal
 */
class UserCommonTest extends TestCase
{
    public function testValidateCanPayee()
    {
        /** @var UserInterface $payer */
        $payer = $this->mock(UserInterface::class);
        $user = new UserCommon();

        $this->assertTrue($user->canPayee($payer));
    }

    public function testValidateCanPayer()
    {
        /** @var UserInterface $payee */
        $payee = $this->mock(UserInterface::class);
        $user = new UserCommon();

        $this->assertTrue($user->canPayer($payee));
    }
}
