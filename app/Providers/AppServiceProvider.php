<?php

declare(strict_types=1);

namespace App\Providers;

use App\Interfaces\UserInterface;
use App\Models\UserCommon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(UserInterface::class, UserCommon::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    }
}
