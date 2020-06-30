<?php

declare(strict_types=1);

namespace App\Service;

final class ReferrerCoefficientMappingService
{
    private const DEFAULT_COEFFICIENT = 1.00;

    private const SOURCE_BING = 'bing.';

    private const SOURCE_GOOGLE = 'google.';

    private const URL_TRANSFORMATIONS = [
        'https' => 'http',
        '://www.' => '://',
        'www.' => '',
    ];

    private const SOURCE_MAPPING = [
        self::SOURCE_BING => 1.1,   // 110% for bing
        self::SOURCE_GOOGLE => 1.2, // 120% for google
    ];

    public ?string $utmSource = null;

    private static function prepareUtmSource(string $source): string
    {
        $source = strtolower($source);
        $source = str_replace(
            array_keys(self::URL_TRANSFORMATIONS),
            array_values(self::URL_TRANSFORMATIONS),
            $source
        );

        $partials = array_merge(
            ['path' => ''],
            parse_url($source)
        );

        return $partials['host'] ?? $partials['path'];
    }

    public function setUtmSource(?string $source): ReferrerCoefficientMappingService
    {
        if (null !== $source) {
            $this->utmSource = self::prepareUtmSource($source);
        }

        return $this;
    }

    private function isUtmSourceValid(): bool
    {
        return (null !== $this->utmSource) && strlen($this->utmSource) > 0;
    }

    private function isSameProvider(string $provider, ?string $source): bool
    {
        if (null === $source) {
            return false;
        }

        return 0 === strpos($provider, $source) || false !== strpos($source, $provider);
    }

    public function getCoefficient(): float
    {
        if ($this->isUtmSourceValid()) {
            foreach (self::SOURCE_MAPPING as $provider => $coefficient) {
                if ($this->isSameProvider($provider, $this->utmSource)) {
                    return $coefficient;
                }
            }
        }

        return self::DEFAULT_COEFFICIENT;
    }
}
