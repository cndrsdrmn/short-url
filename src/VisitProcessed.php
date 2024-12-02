<?php

namespace Cndrsdrmn\ShortUrl;

use Illuminate\Queue\SerializesModels;

class VisitProcessed
{
    use SerializesModels;

    /**
     * Create a new VisitProcessed
     */
    public function __construct(public string $link, public string $ip, public ?string $referer = null)
    {
        //
    }
}
