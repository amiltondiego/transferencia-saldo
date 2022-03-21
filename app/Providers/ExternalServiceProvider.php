<?php

declare(strict_types=1);

namespace App\Providers;

use App\Integrations\ExternalAuthorizationDefault;
use App\Integrations\ExternalNotifyDefault;
use App\Interfaces\ExternalAuthorizationInterface;
use App\Interfaces\ExternalNotifyInterface;
use Illuminate\Support\ServiceProvider;

class ExternalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ExternalAuthorizationInterface::class, ExternalAuthorizationDefault::class);
        $this->app->bind(ExternalNotifyInterface::class, ExternalNotifyDefault::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
