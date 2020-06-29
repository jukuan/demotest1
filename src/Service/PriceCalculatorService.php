<?php

declare(strict_types=1);

namespace App\Service;

final class PriceCalculatorService
{
    private float $basePrice = 0;

    private ?string $referrer = null;

    private ReferrerCoefficientMappingService $coefficientMappingService;

    public function __construct(ReferrerCoefficientMappingService $coefficientMappingService)
    {
        $this->coefficientMappingService = $coefficientMappingService;
    }

    /**
     * @return float|int
     */
    public function getBasePrice()
    {
        return $this->basePrice;
    }

    /**
     * @return PriceCalculatorService
     */
    public function setBasePrice(float $basePrice)
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    public function getReferrer(): ?string
    {
        return $this->referrer;
    }

    public function setReferrer(?string $referrer): PriceCalculatorService
    {
        $this->referrer = $referrer;

        return $this;
    }

    public function getCoefficient(): float
    {
        return $this->coefficientMappingService
            ->setUtmSource($this->referrer)
            ->getCoefficient();
    }

    public function getPersonalPrice(): float
    {
        return $this->getBasePrice() * $this->getCoefficient();
    }
}
