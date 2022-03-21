<?php

declare(strict_types=1);

namespace App\Interfaces;

interface ExternalNotifyInterface
{
    public function consult(UserInterface $user): bool;
}
