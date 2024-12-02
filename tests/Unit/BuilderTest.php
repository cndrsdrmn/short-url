<?php

use Cndrsdrmn\ShortUrl\Builder;
use Cndrsdrmn\ShortUrl\LinkResult;

it('create a short link without any config', function (): void {
    $result = (new Builder)
        ->destination('https://example.com')
        ->create();

    expect($result)
        ->toBeInstanceOf(LinkResult::class)
        ->and($result->link->destination)
        ->toBe('https://example.com');
});

it('create a short link with custom available at', function (): void {
    $result = (new Builder('https://example.com'))
        ->availableAt(now()->addHours())
        ->create();

    expect($result)
        ->toBeInstanceOf(LinkResult::class)
        ->and($result->link->available_at->isFuture())
        ->toBeTrue();
});

it('create a short link with expires one hour', function (): void {
    $result = (new Builder('https://example.com'))
        ->expiresAt(now()->addHour())
        ->create();

    expect($result)
        ->toBeInstanceOf(LinkResult::class)
        ->and($result->link->expires_at->isFuture())
        ->toBeTrue();
});

it('create a short link possible to merge query params', function (): void {
    $result = (new Builder('https://example.com?foo=bar'))
        ->queries(['baz' => 'boom'])
        ->create();

    expect($result)
        ->toBeInstanceOf(LinkResult::class)
        ->and($result->link->queries)
        ->toBe(['baz' => 'boom', 'foo' => 'bar']);
});

it('short link is possible for single use', function (): void {
    $result = (new Builder('https://example.com'))
        ->singleUse()
        ->create();

    expect($result)
        ->toBeInstanceOf(LinkResult::class)
        ->and($result->link->is_single_use)
        ->toBeTrue();
});

it('create a short link with custom headers', function (): void {
    $result = (new Builder('https://example.com'))
        ->headers(['foo' => 'bar'])
        ->create();

    expect($result)
        ->toBeInstanceOf(LinkResult::class)
        ->and($result->link->headers->has('foo'))
        ->toBeTrue();
});

it('create an short link using all options', function (): void {
    $builder = new Builder('https://example.com', [
        'headers' => ['foo' => 'bar'],
        'queries' => ['baz' => 'boom'],
        'expires_at' => now()->addHour(),
        'available_at' => now()->addMinute(),
        'is_single_use' => true,
    ]);

    $result = $builder->create();

    expect($result)
        ->toBeInstanceOf(LinkResult::class)
        ->and($result->link->is_single_use)
        ->toBeTrue()
        ->and($result->link->headers->has('foo'))
        ->toBeTrue()
        ->and($result->link->queries)
        ->toBe(['baz' => 'boom'])
        ->and($result->link->expires_at->isFuture())
        ->toBeTrue()
        ->and($result->link->available_at->isFuture())
        ->toBeTrue();
});
