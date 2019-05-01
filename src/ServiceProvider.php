<?php

namespace E2Consult\IGApi;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind(Client::class, function () {
            $credentials = new Credentials(
                config('services.IG.username'),
                config('services.IG.password'),
                config('services.IG.api_key')
            );

            return ClientFactory::createRestClient($credentials);
        });

        $this->app->alias(Client::class, 'ig-api-client');
    }
}
