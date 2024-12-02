<?php

use Cndrsdrmn\ShortUrl\Builder;
use Cndrsdrmn\ShortUrl\Link;
use Cndrsdrmn\ShortUrl\LinkResult;
use Cndrsdrmn\ShortUrl\ShortUrl;
use Illuminate\Support\Facades\Route;

it('disable the registration of the default routes', function (): void {
    expect(ShortUrl::$registerRoute)
        ->toBeTrue()
        ->and(Route::has('short-url'))
        ->toBeTrue();

    ShortUrl::ignoreRoutes();

    expect(ShortUrl::$registerRoute)->toBeFalse();

});

it('create short url using builder', function (): void {
    $builder = ShortUrl::make('https://example.com', [
        'headers' => ['Content-Type' => 'text/plain'],
    ]);

    expect($builder)->toBeInstanceOf(Builder::class);

    $result = $builder->create();

    expect($result)
        ->toBeInstanceOf(LinkResult::class)
        ->and($result->accessLink)
        ->toBeString()
        ->and($result->link)
        ->toBeInstanceOf(Link::class);
});

it('create short url using shorten', function (): void {
    $shorten = ShortUrl::shorten('https://example.com/foo/bar?cool=fast');

    expect($shorten)->toBeString();
});

it('create short url using custom token', function (): void {
    $token = uniqid();

    ShortUrl::encryptTokenUsing(fn (): string => $token);

    $shorten = ShortUrl::shorten('https://example.com/foo/bar?cool=fast');

    expect($shorten)
        ->toBeString()
        ->toContain($token);
});
