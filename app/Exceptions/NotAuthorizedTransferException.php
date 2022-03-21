<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Interfaces\HttpExceptionInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class NotAuthorizedTransferException extends Exception implements HttpExceptionInterface
{
    public function status(): int
    {
        return JsonResponse::HTTP_BAD_REQUEST;
    }

    public function contentMessage(): array
    {
        return [
            'message' => 'not authorized transfer.',
        ];
    }
}
