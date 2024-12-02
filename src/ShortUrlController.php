<?php

namespace Cndrsdrmn\ShortUrl;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShortUrlController
{
    /**
     * Handle incoming request given a token.
     */
    public function __invoke(Request $request, string $token): RedirectResponse
    {
        $link = Link::query()->where('token', $token)->firstOrFail();

        $this->ensureIsAllowedToVisit($link);

        event(new VisitProcessed($link->getKey(), $request->ip(), $request->header('referer')));

        return redirect(
            to: $link->fullUrl(),
            headers: $link->headers
                ->mapWithKeys(fn ($value, $key) => ['X-'.$key => $value])
                ->all()
        );
    }

    /**
     * Determine the given link is allowed to visit.
     */
    protected function ensureIsAllowedToVisit(Link $link): void
    {
        $isBot = config()->boolean('short-url.should_block_bots', true) && Device::isBot();

        if ($isBot || $link->available_at->isFuture() || $link->expires_at?->isPast()) {
            abort(404);
        }

        if ($link->is_single_use) {
            $link->forceFill(['expires_at' => now()])->saveQuietly();
        }
    }
}
