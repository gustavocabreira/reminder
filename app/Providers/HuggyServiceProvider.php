<?php

namespace App\Providers;

use App\Services\HuggySocialiteProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory as Socialite;

class HuggyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $socialite = $this->app->make(Socialite::class);

        $socialite->extend('huggy', function ($app) use ($socialite) {
            $config = $app['config']['services.huggy'] ?? [];

            return $socialite->buildProvider(HuggySocialiteProvider::class, [
                'client_id' => $config['client_id'] ?? '',
                'client_secret' => $config['client_secret'] ?? '',
                'redirect' => $config['redirect'] ?? '',
                'scopes' => $config['scopes'] ?? [],
            ]);
        });
    }
}
