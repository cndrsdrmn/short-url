<?php

namespace Cndrsdrmn\ShortUrl;

use DateInterval;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

/**
 * Class Builder
 *
 * A fluent interface for building short URLs with customizable options.
 */
final class Builder implements Arrayable
{
    /**
     * The time when the URL becomes available.
     */
    private ?Carbon $availableAt = null;

    /**
     * The time when the URL expires.
     */
    private ?Carbon $expiresAt = null;

    /**
     * Headers to be sent with the URL request.
     */
    private array $headers = [];

    /**
     * Determines if the URL is single-use.
     */
    private bool $isSingleUse = false;

    /**
     * Query parameters to append to the URL.
     */
    private array $queries = [];

    /**
     * Create a new Builder instance.
     */
    public function __construct(private ?string $destination = null, array $options = [])
    {
        $this->fillOptions($options);
    }

    /**
     * Set the available time for the URL.
     *
     * @param  Carbon|DateInterval|int|string  $availableAt
     */
    public function availableAt($availableAt): Builder
    {
        $this->availableAt = $this->parseToDateTime($availableAt);

        return $this;
    }

    /**
     * Create the short URL and return the result.
     */
    public function create(): LinkResult
    {
        $link = Link::query()->create($this->validate());

        $this->resetOptions();

        return new LinkResult($link->token, $link->refresh());
    }

    /**
     * Set the destination URL.
     */
    public function destination(string $destination): Builder
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Set the expiration time for the URL.
     *
     * @param  Carbon|DateInterval|int|string  $expiresAt
     */
    public function expiresAt($expiresAt): Builder
    {
        $this->expiresAt = $this->parseToDateTime($expiresAt);

        return $this;
    }

    /**
     * Set headers for the URL request.
     */
    public function headers(array $headers): Builder
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Set query parameters for the URL.
     */
    public function queries(array $queries): Builder
    {
        $this->queries = $queries;

        return $this;
    }

    /**
     * Set the URL as single-use.
     */
    public function singleUse(bool $isSingleUse = true): Builder
    {
        $this->isSingleUse = $isSingleUse;

        return $this;
    }

    /**
     * Convert the builder's configuration to an array.
     */
    public function toArray(): array
    {
        return $this->sanitize([
            'destination' => $this->destination,
            'headers' => $this->headers,
            'queries' => $this->queries,
            'expires_at' => $this->expiresAt,
            'available_at' => $this->availableAt,
            'is_single_use' => $this->isSingleUse,
        ]);
    }

    /**
     * Fill builder options from an array.
     */
    private function fillOptions(array $options = []): void
    {
        if (isset($options['headers']) && is_array($options['headers'])) {
            $this->headers($options['headers']);
        }

        if (isset($options['queries']) && is_array($options['queries'])) {
            $this->queries($options['queries']);
        }

        if (isset($options['is_single_use'])) {
            $this->singleUse((bool) $options['is_single_use']);
        }

        if (isset($options['available_at'])) {
            $this->availableAt($options['available_at']);
        }

        if (isset($options['expires_at'])) {
            $this->expiresAt($options['expires_at']);
        }
    }

    /**
     * Parse a value into a Carbon date-time instance.
     *
     * @param  DateInterval|int|string  $value
     */
    private function parseToDateTime($value): Carbon
    {
        return match (true) {
            is_int($value) => Carbon::now()->addMinutes($value),
            $value instanceof DateInterval => Carbon::now()->add($value),
            default => Carbon::parse($value),
        };
    }

    /**
     * Reset all builder options to their defaults.
     */
    private function resetOptions(): void
    {
        $this->headers = [];
        $this->queries = [];
        $this->isSingleUse = false;
        $this->destination = null;
        $this->expiresAt = null;
        $this->availableAt = null;
    }

    /**
     * Sanitize builder data for storage.
     */
    private function sanitize(array $data): array
    {
        if ($queries = parse_url((string) $data['destination'], PHP_URL_QUERY)) {
            $data['destination'] = rtrim((string) $data['destination'], '?'.$queries);
            parse_str($queries, $queries);
            $data['queries'] = array_merge($data['queries'], $queries);
        }

        $data['available_at'] ??= Carbon::now()->addSecond();

        return $data;
    }

    /**
     * Validate the builder's configuration.
     */
    private function validate(): array
    {
        $schemas = config()->array('short-url.allowed_schemas', []);

        return Validator::validate($this->toArray(), [
            'destination' => 'required|url:'.implode(',', $schemas),
            'headers' => 'array',
            'queries' => 'array',
            'expires_at' => 'nullable|date|after:available_at',
            'available_at' => 'date|after:now',
            'is_single_use' => 'boolean',
        ]);
    }
}
