<?php

declare(strict_types=1);

namespace App\Interfaces;

/**
 * @property int $id
 */
interface UserInterface
{
    public function canPayee(self $payer): bool;

    public function canPayer(self $payee): bool;
}
