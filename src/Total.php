<?php

declare(strict_types=1);

namespace domipoppe\sharkpay;

/**
 * Class Total
 *
 * @package domipoppe\sharkpay
 */
class Total
{
    private Price $totalPriceNetto;
    private Price $totalPriceTax;
    private Price $totalPriceBrutto;
    private array $discountPositions;
    private array $taxPositions;

    /**
     * @param Price $totalPriceNetto
     * @param Price $totalPriceTax
     * @param Price $totalPriceBrutto
     * @param array $discountPositions
     * @param array $taxPositions
     */
    public function __construct(Price $totalPriceNetto, Price $totalPriceTax, Price $totalPriceBrutto, array $discountPositions, array $taxPositions)
    {
        $this->totalPriceNetto = $totalPriceNetto;
        $this->totalPriceTax = $totalPriceTax;
        $this->totalPriceBrutto = $totalPriceBrutto;
        $this->discountPositions = $discountPositions;
        $this->taxPositions = $taxPositions;
    }

    /**
     * @return float
     */
    public function getNetto(): float
    {
        return $this->totalPriceNetto->getBrutto();
    }

    /**
     * @return string
     */
    public function getNettoAsString(): string
    {
        return $this->totalPriceBrutto->getNettoAsString();
    }

    /**
     * @return float
     */
    public function getTax(): float
    {
        return $this->totalPriceTax->getBrutto();
    }

    /**
     * @return float
     */
    public function getBrutto(): float
    {
        return $this->totalPriceBrutto->getBrutto();
    }

    /**
     * @return string
     */
    public function getBruttoAsString(): string
    {
        return $this->totalPriceBrutto->getBruttoAsString();
    }

    /**
     * @return array
     */
    public function getDiscountPositions(): array
    {
        return $this->discountPositions;
    }

    /**
     * @return array
     */
    public function getTaxPositions(): array
    {
        return $this->taxPositions;
    }
}