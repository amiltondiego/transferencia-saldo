<?php

declare(strict_types=1);

namespace App\Models;

class WalletType
{
    public static function processing(): int
    {
        return 1;
    }

    public static function success(): int
    {
        return 2;
    }

    public static function fail(): int
    {
        return 3;
    }
}
