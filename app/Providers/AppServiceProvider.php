<?php

namespace App\Providers;

use App\Services\Nationalize\NationalizeClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(NationalizeClient::class, fn () => new NationalizeClient(
            baseUrl: config('services.nationalize.url'),
            timeout: (int) config('services.nationalize.timeout'),
        ));
    }

    public function boot(): void
    {
        //
    }
}
