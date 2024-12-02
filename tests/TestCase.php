<?php

namespace Tests;

use Cndrsdrmn\ShortUrl\ShortUrlServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

use function Orchestra\Testbench\workbench_path;

abstract class TestCase extends BaseTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(
            workbench_path('database/migrations')
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set([
            'cache.default' => 'array',

            'database.default' => 'testbench',
            'database.connections.testbench' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ],

            'unique_attributes' => [
                'prefix' => '',
                'suffix' => '',
                'separator' => '_',
                'max_retries' => 100,
                'length' => 8,
                'format' => 'bothify',
            ],
        ]);
    }

    /**
     * {@inheritDoc}
     */
    protected function getPackageProviders($app): array
    {
        return [ShortUrlServiceProvider::class];
    }
}
