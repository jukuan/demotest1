<?php

declare(strict_types=1);

namespace App\Service;

final class ReferrerCoefficientMappingService
{
    private const SOURCE_BING = 'bing.';

    private const SOURCE_GOOGLE = 'google.';

    private const SOURCE_MAPPING = [
        self::SOURCE_BING => 1.1,   // 110% for bing
        self::SOURCE_GOOGLE => 1.2, // 120% for google
    ];

    public ?string $utmSource = null;

    private function prepareUtmSource(string $source): string
    {
        $source = str_replace(['https'], ['http'], $source);
        $source = str_replace(['://www.'], ['://'], $source);

        $partials = parse_url($source);

        return $partials['host'] ?? '';
    }

    public function setUtmSource(?string $source):  ReferrerCoefficientMappingService
    {
        if (null !== $source) {
            $this->utmSource = $this->prepareUtmSource($source);
        }

        return $this;
    }

    public function getCoefficient(): float
    {
        foreach (self::SOURCE_MAPPING as $provider => $coefficient) {
            if (false !== strpos($provider, $this->utmSource)) {
                return $coefficient;
            }
        }

        return 1.00;
    }
}
