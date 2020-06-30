<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class PriceCalculatorService
{
    private const SESSION_MAX_IDLE_TIME = 432000; // 5 days

    private float $basePrice = 0;

    private ?string $referrer = null;

    private ReferrerCoefficientMappingService $coefficientMappingService;

    private SessionInterface $session;

    public function __construct(
        ReferrerCoefficientMappingService $coefficientMappingService,
        SessionInterface $session
    ) {
        $this->coefficientMappingService = $coefficientMappingService;
        $this->session = $session;
    }

    public function getBasePrice(): float
    {
        return $this->basePrice;
    }

    public function setBasePrice(float $basePrice): PriceCalculatorService
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    public function getReferrer(): ?string
    {
        return $this->session->get('http_referer', $this->referrer);
    }

    public function setReferrer(?string $referrer): PriceCalculatorService
    {
        if (null !== $referrer) {
            $this->referrer = $referrer;
            $this->session->set('http_referer', $referrer);
        }

        return $this;
    }

    private function isSessionExpired(): bool
    {
        return time() - $this->session->getMetadataBag()->getLastUsed() > self::SESSION_MAX_IDLE_TIME;
    }

    public function calculateCoefficient(): float
    {
        $referrer = $this->getReferrer();

        if ($this->isSessionExpired()) {
            $referrer = null;
        }

        return $this->coefficientMappingService
            ->setUtmSource($referrer)
            ->getCoefficient();
    }

    public function getPersonalPrice(): float
    {
        return $this->getBasePrice() * $this->calculateCoefficient();
    }
}
