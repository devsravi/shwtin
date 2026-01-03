<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Scopes\OwnerScope;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name
 * @property string|null $domain
 * @property string $destination_url
 * @property string $default_short_url
 * @property string $url_key
 * @property bool $single_use
 * @property bool $forward_query_params
 * @property bool $track_visits
 * @property int $redirect_status_code
 * @property bool $track_ip_address
 * @property bool $track_operating_system
 * @property bool $track_operating_system_version
 * @property bool $track_browser
 * @property bool $track_browser_version
 * @property bool $track_referer_url
 * @property string|null $referer_url
 * @property bool $track_device_type
 * @property bool $is_guest
 * @property CarbonInterface $activated_at
 * @property CarbonInterface|null $deactivated_at
 * @property CarbonInterface|null $deleted_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */


#[ScopedBy([OwnerScope::class])]
final class Url extends Model
{
    /**
     * @use HasFactory<\Database\Factories\UrlFactory> 
     * @use softDeletes<\Illuminate\Database\Eloquent\SoftDeletes>
     */
    use HasFactory, SoftDeletes;


    /**
     * The table associated with the model.
     *
     * @var string
     */

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'single_use' => 'boolean',
        'forward_query_params' => 'boolean',
        'track_visits' => 'boolean',
        'track_ip_address' => 'boolean',
        'track_operating_system' => 'boolean',
        'track_operating_system_version' => 'boolean',
        'track_browser' => 'boolean',
        'track_browser_version' => 'boolean',
        'track_referer_url' => 'boolean',
        'track_device_type' => 'boolean',
        'is_guest' => 'boolean',
        'activated_at' => 'datetime',
        'deactivated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    /**
     * A short URL can be created by.
     *
     * @return BelongTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A short URL can have many visits.
     *
     * @return HasMany<Visit>
     */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Get cache key for URL
     */
    public static function getCacheKey(string $urlKey): string
    {
        return "url:key:{$urlKey}";
    }

    /**
     * Find URL by key - ALWAYS from cache
     *
     * A helper method that can be used for finding a URL model with the
     * given URL key.
     */
    public static function findByKey(string $urlKey): ?self
    {
        $cacheKey = self::getCacheKey($urlKey);

        return Cache::get($cacheKey);
    }


    /**
     * A helper method that can be used for finding all the ShortURL models
     * with the given destination URL.
     *
     * @return Collection<int, URL>
     */
    public static function findByDestinationURL(string $destinationURL): Collection
    {
        return self::where('destination_url', $destinationURL)->get();
    }

    /**
     * Check if URL is currently active
     */
    public function isActive(): bool
    {
        // Check activation date
        if ($this->activated_at && now()->isBefore($this->activated_at)) {
            return false;
        }

        // Check deactivation date
        if ($this->deactivated_at && now()->isAfter($this->deactivated_at)) {
            return false;
        }

        // Check single-use status
        if ($this->single_use && $this->visits()->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Cache this URL instance
     */
    public function cacheUrl(): void
    {
        $cacheKey = self::getCacheKey($this->url_key);

        // Cache for 24 hours (86400 seconds)
        Cache::put($cacheKey, $this, 86400);
    }

    /**
     * Remove this URL from cache
     */
    public function forgetCache(): void
    {
        $cacheKey = self::getCacheKey($this->url_key);
        Cache::forget($cacheKey);
    }

    /**
     * Model events - auto cache management
     */
    protected static function booted(): void
    {
        // Cache URL when created
        static::created(function (Url $url) {
            $url->cacheUrl();
        });

        // Update cache when URL is updated
        static::updated(function (Url $url) {
            $url->forgetCache();
            $url->cacheUrl();
        });

        // Remove from cache when deleted
        static::deleted(function (Url $url) {
            $url->forgetCache();
        });

        // Update cache when URL is saved (covers both create and update)
        static::saved(function (Url $url) {
            $url->cacheUrl();
        });
    }


    /**
     * A helper method to determine whether if tracking is currently enabled
     * for the short URL.
     */
    public function trackingEnabled(): bool
    {
        return $this->track_visits;
    }

    /**
     * Return an array containing the fields that are set to be tracked for the
     * short URL.
     *
     * @return array<int, string>
     */
    public function trackingFields(): array
    {
        $fields = [];

        if ($this->track_ip_address) {
            $fields[] = 'ip_address';
        }

        if ($this->track_operating_system) {
            $fields[] = 'operating_system';
        }

        if ($this->track_operating_system_version) {
            $fields[] = 'operating_system_version';
        }

        if ($this->track_browser) {
            $fields[] = 'browser';
        }

        if ($this->track_browser_version) {
            $fields[] = 'browser_version';
        }

        if ($this->track_referer_url) {
            $fields[] = 'referer_url';
        }

        if ($this->track_device_type) {
            $fields[] = 'device_type';
        }

        return $fields;
    }


    /**
     * Scope a query to get the records Between.
     * 
     * @param Builder $query
     * @param string $startDate
     * @param string $endDate
     * @return Builder
     */
    #[Scope]
    public function forDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }



    /**
     * Get all of the tags for the Url.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
