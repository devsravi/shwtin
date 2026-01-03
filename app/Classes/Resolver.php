<?php

declare(strict_types=1);

namespace App\Classes;

use App\Exceptions\ValidationException;
use App\Interfaces\UserAgentDriver;
use App\Models\Url;
use App\Models\Visit;

class Resolver
{
    private UserAgentDriver $userAgentDriver;

    /**
     * @throws ValidationException
     */
    public function __construct(UserAgentDriver $userAgentDriver)
    {
        $this->userAgentDriver = $userAgentDriver;
    }

    /**
     * Handle the visit. Check that the visitor is allowed to visit the URL. If
     * the short URL has tracking enabled, track the visit in the database.
     * If this method is executed successfully, return true.
     */
    public function handleVisit(
        string $ip,
        ?string $userAgent,
        ?string $referer,
        array $headers,
        Url $shortURL
    ): bool {

        $this->recordVisit(
            ip: $ip,
            userAgent: $userAgent,
            referer: $referer,
            headers: $headers,
            shortURL: $shortURL
        );
        return true;
    }

    /**
     * Determine whether if the visitor is allowed access to the URL. If the short
     * URL is a single use URL and has already been visited, return false. If
     * the URL is not activated yet, return false. If the URL has been
     * deactivated, return false.
     */
    protected function shouldAllowAccess(Url $shortURL): bool
    {
        if ($shortURL->single_use && $shortURL->visits()->exists()) {
            return false;
        }

        if (now()->isBefore($shortURL->activated_at)) {
            return false;
        }

        if ($shortURL->deactivated_at && now()->isAfter($shortURL->deactivated_at)) {
            return false;
        }

        return true;
    }

    /**
     * Record the visit in the database. We record basic information of the visit if
     * tracking even if tracking is not enabled. We do this so that we can check
     * if single-use URLs have been visited before.
     */
    protected function recordVisit(
        string $ip,
        ?string $userAgent,
        ?string $referer,
        array $headers,
        Url $shortURL
    ): Visit {
        $visit = new Visit();

        $visit->user_id = $shortURL->user_id;
        $visit->url_id = $shortURL->id;
        $visit->user_agent = $userAgent;
        $visit->request_headers = $headers;
        $visit->visited_at = now();
        // Track Geo Location
        $this->trackGeoLocation(visit: $visit, ip: $ip);
        if ($shortURL->track_visits) {
            $this->trackVisit(ip: $ip, userAgent: $userAgent, referer: $referer, shortURL: $shortURL, visit: $visit);
        }

        $visit->save();

        return $visit;
    }

    /**
     * Check which fields should be tracked and then store them if needed. Otherwise, add
     * them as null.
     */
    protected function trackVisit(
        string $ip,
        ?string $userAgent,
        ?string $referer,
        Url $shortURL,
        Visit $visit,
    ): void {
        $userAgentParser = $this->userAgentDriver->usingUserAgentString($userAgent);

        if ($shortURL->track_ip_address) {
            $visit->ip_address = $ip;
        }

        if ($shortURL->track_operating_system) {
            $visit->operating_system = $userAgentParser->getOperatingSystem();
            $visit->operating_system_alias = $userAgentParser->getOperatingSystemAlias();
        }

        if ($shortURL->track_operating_system_version) {
            $visit->operating_system_version = $userAgentParser->getOperatingSystemVersion();
        }

        if ($shortURL->track_browser) {
            $visit->browser = $userAgentParser->getBrowser();
            $visit->engine = $userAgentParser->getRenderingEngine();
        }

        if ($shortURL->track_browser_version) {
            $visit->browser_version = $userAgentParser->getBrowserVersion();
        }

        if ($shortURL->track_referer_url) {
            $visit->referer_url = $referer;
        }

        if ($shortURL->track_device_type) {
            $visit->device_type = $this->guessDeviceType($userAgentParser);
            $visit->device_manufacturer = $userAgentParser->getDeviceManufacturer();
            $visit->device_model = $userAgentParser->getDeviceModel();
        }
    }
    /**
     * Track the geo location data by ip, add
     * them as db.
     */
    protected function trackGeoLocation(Visit $visit, string $ip): void
    {
        $cacheKey = "geoip:{$ip}";

        $geoIpLocation = cache()->remember($cacheKey, now()->addHours(24), function () use ($ip) {
            return geoip()->getLocation($ip);
        });

        $visit->iso_code = $geoIpLocation->iso_code;
        $visit->country = $geoIpLocation->country;
        $visit->city = $geoIpLocation->city;
        $visit->state = "{$geoIpLocation->state_name} ({$geoIpLocation->state})";
        $visit->postal_code = $geoIpLocation->postal_code;
        $visit->timezone = $geoIpLocation->timezone;
        $visit->lat = $geoIpLocation->lat;
        $visit->long = $geoIpLocation->lon;
        $visit->continent = $geoIpLocation->continent;
        $visit->currency = $geoIpLocation->currency;
        $visit->is_default = $geoIpLocation->default;
    }

    /**
     * Guess and return the device type that was used to visit the short URL. Null
     * will be returned if we cannot determine the device type.
     */
    protected function guessDeviceType(UserAgentDriver $userAgentParser): ?string
    {
        return match (true) {
            $userAgentParser->isDesktop() => Visit::DEVICE_TYPE_DESKTOP,
            $userAgentParser->isMobile() => Visit::DEVICE_TYPE_MOBILE,
            $userAgentParser->isTablet() => Visit::DEVICE_TYPE_TABLET,
            $userAgentParser->isRobot() => Visit::DEVICE_TYPE_ROBOT,
            default => null,
        };
    }
}
