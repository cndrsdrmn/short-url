<?php

namespace Cndrsdrmn\ShortUrl;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

use function Illuminate\Filesystem\join_paths;

final class LinkResult implements Arrayable, Jsonable
{
    /**
     * Create a new LinkResult instance.
     */
    public function __construct(public string $accessLink, public Link $link)
    {
        $this->resolve($accessLink);
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'accessLink' => $this->accessLink,
            'link' => $this->link,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Resolve the route given access link.
     */
    private function resolve(string $accessLink): void
    {
        $route = join_paths(
            config('short-url.default_url') ?? config('app.url'),
            config('short-url.prefix'),
            $accessLink
        );

        if (ShortUrl::$registerRoute) {
            $route = route('short-url', ['token' => $accessLink]);
        }

        $this->accessLink = $route;
    }
}
