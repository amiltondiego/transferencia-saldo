<?php

declare(strict_types=1);

namespace App\Models;

class UserType
{
    public static function common(): int
    {
        return 1;
    }

    public static function shopkeeper(): int
    {
        return 2;
    }
}
