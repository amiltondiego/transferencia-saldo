<?php

declare(strict_types=1);

namespace App\Interfaces;

interface ExternalAuthorizationInterface
{
    public function consult(UserInterface $user): bool;
}
