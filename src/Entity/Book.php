<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @package App\Entity
 * @ORM\Entity()
 */
final class Book {
    /**
     * @var float
     * @ORM\Column(type="float")
     */
    protected float $basePrice;

    /**
     * @return float
     */
    public function getBasePrice(): float
    {
        return $this->basePrice;
    }

    /**
     * @param float $basePrice
     * @return Book
     */
    public function setBasePrice(float $basePrice): Book
    {
        $this->basePrice = $basePrice;

        return $this;
    }
}