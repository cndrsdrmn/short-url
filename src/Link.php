<?php

namespace Cndrsdrmn\ShortUrl;

use Cndrsdrmn\EloquentUniqueAttributes\HasEnforcesUniqueAttributes;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read string $token
 * @property-read string $destination
 * @property-read \Illuminate\Support\Collection $headers
 * @property-read array $queries
 * @property-read \Illuminate\Support\Carbon $available_at
 * @property-read \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Support\Carbon|null $expires_at
 * @property-read \Illuminate\Support\Carbon $updated_at
 * @property-read bool $is_single_use
 */
class Link extends Model
{
    use HasEnforcesUniqueAttributes, HasUuids;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'links';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'token',
        'destination',
        'headers',
        'queries',
        'expires_at',
        'available_at',
        'is_single_use',
    ];

    /**
     * {@inheritDoc}
     */
    public function enforcedUniqueColumns(): array
    {
        return [
            'token' => [
                'format' => config()->string('short-url.token_format'),
                'length' => config()->integer('short-url.token_length'),
            ],
        ];
    }

    /**
     * Get full url of destination url.
     */
    public function fullUrl(): string
    {
        return rtrim($this->destination.'?'.http_build_query($this->queries), '?');
    }

    /**
     * {@inheritDoc}
     */
    public function getConnectionName(): ?string
    {
        return config('short-url.connection');
    }

    /**
     * {@inheritdoc}
     */
    protected function casts(): array
    {
        return [
            'available_at' => 'datetime',
            'expires_at' => 'datetime',
            'queries' => 'array',
            'headers' => AsCollection::class,
            'is_single_use' => 'boolean',
        ];
    }
}
