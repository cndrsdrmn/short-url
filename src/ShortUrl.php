<?php

namespace Cndrsdrmn\ShortUrl;

class ShortUrl
{
    /**
     * Indicates whether the default route should be registered.
     */
    public static bool $registerRoute = true;

    /**
     * A callback for encrypting the short URL token.
     *
     * @var ?callable
     */
    public static $tokenEncryptionKeyCallback;

    /**
     * Disable the registration of the default routes.
     * This can be useful when you want to define custom routes instead of using the package defaults.
     */
    public static function ignoreRoutes(): void
    {
        static::$registerRoute = false;
    }

    /**
     * Create a new Builder instance for generating short URLs.
     */
    public static function make(?string $destination = null, array $options = []): Builder
    {
        return new Builder($destination, $options);
    }

    /**
     * Create and return a short URL for the given destination.
     */
    public static function shorten(string $destination): string
    {
        return static::make($destination)->create()->accessLink;
    }

    /**
     * Set a callback for encrypting the short URL token.
     * The callback should accept and return a string for the token.
     */
    public static function encryptTokenUsing(callable $callback): void
    {
        static::$tokenEncryptionKeyCallback = $callback;
    }
}
