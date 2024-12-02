<?php

use Cndrsdrmn\ShortUrl\Link;
use Cndrsdrmn\ShortUrl\LinkResult;

beforeEach(function (): void {
    $this->link = Link::query()->create([
        'destination' => fake()->url(),
        'available_at' => now()->subDay(),
    ]);

    $this->result = new LinkResult($this->link->token, $this->link);
});

it('should be array return', function (): void {
    expect($this->result->toArray())
        ->toBeArray()
        ->toHaveCount(2)
        ->toHaveKeys(['accessLink', 'link']);
});

it('should be json return', function (): void {
    expect($this->result->toJson())
        ->json()
        ->accessLink->toBeString()
        ->link->toBeArray();
});
