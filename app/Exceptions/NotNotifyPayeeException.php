<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Interfaces\JobExceptionInterface;
use Exception;

class NotNotifyPayeeException extends Exception implements JobExceptionInterface
{
    public function __construct()
    {
        parent::__construct($this->contentMessage());
    }

    public function contentMessage(): string
    {
        return implode(' | ', [
            'not notify payee.',
        ]);
    }
}
