<?php

use Cndrsdrmn\ShortUrl\ShortUrl;

it('short link without any configuration', function (): void {
    $link = ShortUrl::shorten('https://example.org');

    $response = $this->get($link);
    $response->assertStatus(302);
    $response->assertRedirect('https://example.org');
});

it('cant access with expired link', function (): void {
    $result = ShortUrl::make('https://example.org')
        ->expiresAt(now()->addDay())
        ->create();

    $result->link
        ->forceFill(['expires_at' => now()->subDays(2)])
        ->save();

    $this->get($result->accessLink)
        ->assertStatus(404);
});

it('cant access if link not available yet', function (): void {
    $result = ShortUrl::make('https://example.org')
        ->availableAt(now()->addDay())
        ->create();

    $this->get($result->accessLink)
        ->assertStatus(404);
});

it('link cant be access twice if the link is single use', function (): void {
    $result = ShortUrl::make('https://example.org')
        ->singleUse()
        ->create();

    $this->get($result->accessLink)
        ->assertStatus(302)
        ->assertRedirect('https://example.org');

    $this->get($result->accessLink)
        ->assertStatus(404);
});
