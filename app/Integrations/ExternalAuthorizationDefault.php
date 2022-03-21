<?php

declare(strict_types=1);

namespace App\Integrations;

use App\Interfaces\ExternalAuthorizationInterface;
use App\Interfaces\UserInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalAuthorizationDefault implements ExternalAuthorizationInterface
{
    public function __construct(
        private string $endpoint = 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6'
    ) {
    }

    public function consult(UserInterface $user): bool
    {
        return in_array('Autorizado', $this->getAuthorization($user), true);
    }

    private function getAuthorization(UserInterface $user): array
    {
        try {
            $response = Http::get($this->endpoint);

            return is_array($response->json()) ? $response->json() : [];
        } catch (\Throwable $th) { // @phpstan-ignore-line
            Log::debug('Error Connection with external Authorization default.', [
                'message' => $th->getMessage(),
            ]);

            return [];
        }
    }
}
