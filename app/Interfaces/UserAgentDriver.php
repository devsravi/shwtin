<?php

declare(strict_types=1);

namespace App\Interfaces;

interface UserAgentDriver
{
    public function usingUserAgentString(?string $userAgentString): self;

    public function getOperatingSystem(): ?string;

    public function getOperatingSystemVersion(): ?string;

    public function getOperatingSystemAlias(): ?string; // An alternative name that is used for readable strings

    public function getBrowser(): ?string;

    public function getBrowserVersion(): ?string;

    public function getRenderingEngine(): ?string;

    public function getDeviceModel(): ?string;

    public function getDeviceManufacturer(): ?string;

    public function getParser();

    public function getDeviceType(): ?string;

    public function isDesktop(): bool;

    public function isMobile(): bool;

    public function isTablet(): bool;

    public function isRobot(): bool;
}
