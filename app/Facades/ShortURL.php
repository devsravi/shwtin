<?php

declare(strict_types=1);

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string|null prefix()
 * @method static array<class-string> middleware()
 * @method static void routes()
 * @method static \App\Classes\Builder destinationUrl(string $url)
 * @method static \App\Classes\Builder singleUse(bool $isSingleUse = true)
 * @method static \App\Classes\Builder secure(bool $isSecure = true)
 * @method static \App\Classes\Builder forwardQueryParams(bool $shouldForwardQueryParams = true)
 * @method static \App\Classes\Builder trackVisits(bool $trackUrlVisits = true)
 * @method static \App\Classes\Builder trackIPAddress(bool $track = true)
 * @method static \App\Classes\Builder trackOperatingSystem(bool $track = true)
 * @method static \App\Classes\Builder trackOperatingSystemVersion(bool $track = true)
 * @method static \App\Classes\Builder trackBrowser(bool $track = true)
 * @method static \App\Classes\Builder trackBrowserVersion(bool $track = true)
 * @method static \App\Classes\Builder trackRefererURL(bool $track = true)
 * @method static \App\Classes\Builder trackDeviceType(bool $track = true)
 * @method static \App\Classes\Builder urlKey(string $key)
 * @method static \App\Classes\Builder keyGenerator(\App\Classes\KeyGenerator $keyGenerator)
 * @method static \App\Classes\Builder redirectStatusCode(int $statusCode)
 * @method static \App\Classes\Builder activateAt(\Carbon\Carbon $activationTime)
 * @method static \App\Classes\Builder deactivateAt(\Carbon\Carbon $deactivationTime)
 * @method static \App\Classes\Builder generateKeyUsing(int $generateUsing)
 * @method static \App\Classes\Builder beforeCreate(\Closure $callback)
 * @method static \App\Models\Url make()
 * @method static array<string,mixed> toArray()
 * @method static \App\Classes\Builder resetOptions()
 * @method static \App\Classes\Builder|mixed when(\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
 * @method static \App\Classes\Builder|mixed unless(\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
 *
 * @see \AshAllenDesign\ShortURL\Classes\Builder
 */
class ShortURL extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'short-url.builder';
    }
}
