<?php

declare(strict_types=1);

namespace App\Interfaces;

interface JobExceptionInterface
{
    public function contentMessage(): string;
}
