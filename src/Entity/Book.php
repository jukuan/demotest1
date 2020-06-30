<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
final class Book
{
    /**
     * @ORM\Column(type="float")
     */
    protected float $basePrice;

    public function __construct()
    {
        $this->basePrice = 0;
    }

    public function getBasePrice(): float
    {
        return $this->basePrice;
    }

    public function setBasePrice(float $basePrice): Book
    {
        $this->basePrice = $basePrice;

        return $this;
    }
}
