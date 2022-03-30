<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories;

use App\Models\UserCommon;
use App\Models\UserShopkeeper;
use App\Repositories\WalletRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @internal
 */
class WalletRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testGetBalance()
    {
        $this->seed();

        $userCommon = UserCommon::where('email', 'user-comum@gmail.com')->first();

        $repository = new WalletRepository();

        $this->assertIsFloat($repository->getBalance($userCommon, 0));
        $this->assertGreaterThanOrEqual(10000.0, $repository->getBalance($userCommon, 0));
    }

    public function testRegisterTransferSuccess()
    {
        $this->seed();

        $userCommon = UserCommon::where('email', 'user-comum@gmail.com')->first();
        $userShopkeeper = UserShopkeeper::where('email', 'lojista@gmail.com')->first();

        $repository = new WalletRepository();

        $this->assertTrue($repository->registerTransfer($userCommon, $userShopkeeper, 100));
    }
}
