<?php

declare(strict_types=1);

namespace App\Models;

use App\Interfaces\UserInterface;

class UserShopkeeper extends User implements UserInterface
{
    public function canPayee(UserInterface $payer): bool
    {
        return true;
    }

    public function canPayer(UserInterface $payee): bool
    {
        return false;
    }
}
