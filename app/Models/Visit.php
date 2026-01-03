<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Scopes\OwnerScope;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $user_id
 * @property int $url_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $operating_system
 * @property string|null $operating_system_alias
 * @property string|null $operating_system_version
 * @property string|null $browser
 * @property string|null $browser_version
 * @property string|null $engine
 * @property string|null $device_type
 * @property string|null $device_manufacturer
 * @property string|null $device_model
 * @property string|null $iso_code
 * @property string|null $country
 * @property string|null $city
 * @property string|null $state
 * @property string|null $postal_code
 * @property string|null $timezone
 * @property string|null $lat
 * @property string|null $long
 * @property string|null $continent
 * @property string|null $currency
 * @property string|null $is_default
 * @property string|null $referer_url
 * @property string|null $parser
 * @property string|null $geo_data
 * @property string|null $request_headers
 * @property CarbonInterface|null $visited_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
#[ScopedBy([OwnerScope::class])]
class Visit extends Model
{
    /**
     * @use HasFactory<\Database\Factories\VisitFactory>
     * @use softDeletes<\Illuminate\Database\Eloquent\SoftDeletes>
     */
    use HasFactory, SoftDeletes;

    /**
     * Device type constants.
     */
    public const DEVICE_TYPE_MOBILE = 'mobile';

    public const DEVICE_TYPE_DESKTOP = 'desktop';

    public const DEVICE_TYPE_TABLET = 'tablet';

    public const DEVICE_TYPE_ROBOT = 'robot';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'integer',
        'url_id' => 'integer',
        'visited_at' => 'datetime',
        'parser' => 'json',
        'geo_data' => 'json',
        'request_headers' => 'json',
    ];

    /**
     * Scope a query to get the records Between.
     */
    #[Scope]
    public function forDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('visited_at', [$startDate, $endDate]);
    }

    /**
     * A URL visit belongs to one specific shortened URL.
     *
     * @return BelongsTo<URL, $this>
     */
    public function shortURL(): BelongsTo
    {
        return $this->belongsTo(Url::class);
    }

    /**
     * A URL visit belongs to one specific user that is created short url.
     *
     * @return BelongsTo<ShortURL, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
