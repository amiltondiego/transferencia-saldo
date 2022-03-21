<?php

declare(strict_types=1);

namespace App\Integrations;

use App\Interfaces\ExternalNotifyInterface;
use App\Interfaces\UserInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalNotifyDefault implements ExternalNotifyInterface
{
    public function __construct(
        private string $endpoint = 'http://o4d9z.mocklab.io/notify'
    ) {
    }

    public function consult(UserInterface $user): bool
    {
        return in_array('Success', $this->sentNotify($user), true);
    }

    private function sentNotify(UserInterface $user): array
    {
        try {
            $response = Http::get($this->endpoint);

            return is_array($response->json()) ? $response->json() : [];
        } catch (\Throwable $th) { // @phpstan-ignore-line
            Log::debug('Error Connection with external notify default.', [
                'message' => $th->getMessage(),
            ]);

            return [];
        }
    }
}
