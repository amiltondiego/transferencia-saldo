<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\ExternalAuthorizationInterface;
use App\Interfaces\UserInterface;

class ExternalAuthorizationService
{
    public function __construct(
        private ExternalAuthorizationInterface $externalAuthorization,
    ) {
    }

    public function hasAuthorization(UserInterface $payer): bool
    {
        return $this->externalAuthorization->consult($payer);
    }
}
