<?php

namespace Cndrsdrmn\ShortUrl;

use DeviceDetector\Cache\LaravelCache;
use DeviceDetector\ClientHints;
use DeviceDetector\DeviceDetector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ShortUrlServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/short-url.php', 'short-url');

        $this->configureDeviceDetector();
    }

    /**
     * Boot any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/short-url.php' => config_path('short-url.php'),
            ], 'short-url-config');

            $this->publishesMigrations([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'short-url-migrations');
        }

        $this->configureTokenEncryptionKeyCallback();

        $this->configureRoute();
    }

    /**
     * Configure the device detector.
     */
    protected function configureDeviceDetector(): void
    {
        $this->app->singleton('device', function ($app) {
            $userAgent = $this->app['request']->userAgent() ?? '';
            $server = $this->app['request']->server() ?? [];
            $client = ClientHints::factory($server);

            return tap(new DeviceDetector($userAgent, $client), function (DeviceDetector $detector): void {
                $detector->setCache(new LaravelCache);
                $detector->parse();
            });
        });
    }

    /**
     * Configure the short url routes.
     */
    protected function configureRoute(): void
    {
        if (ShortUrl::$registerRoute) {
            Route::name('short-url')
                ->middleware(config()->array('short-url.middleware'))
                ->prefix(config()->string('short-url.prefix'))
                ->get('{token}', ShortUrlController::class);
        }
    }

    /**
     * Configure handler for callback token encryption.
     */
    protected function configureTokenEncryptionKeyCallback(): void
    {
        Event::listen('eloquent.enforcing: '.Link::class, function (Model $model): bool {
            if (blank(ShortUrl::$tokenEncryptionKeyCallback)) {
                return false;
            }

            $model->setAttribute('token', value(ShortUrl::$tokenEncryptionKeyCallback));

            return true;
        });
    }
}
