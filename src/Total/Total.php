<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Total;

use domipoppe\sharkpay\Discount;
use domipoppe\sharkpay\Price;
use domipoppe\sharkpay\Tax\TaxPosition;

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
    /** @var Discount[] $discountPositions */
    private array $discountPositions;
    /** @var TaxPosition[] $taxPositions */
    private array $taxPositions;

    /**
     * @param Price         $totalPriceNetto
     * @param Price         $totalPriceTax
     * @param Price         $totalPriceBrutto
     * @param Discount[]    $discountPositions
     * @param TaxPosition[] $taxPositions
     */
    public function __construct(
        Price $totalPriceNetto,
        Price $totalPriceTax,
        Price $totalPriceBrutto,
        array $discountPositions,
        array $taxPositions
    ) {
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
     * @return Discount[]
     */
    public function getDiscountPositions(): array
    {
        return $this->discountPositions;
    }

    /**
     * @return TaxPosition[]
     */
    public function getTaxPositions(): array
    {
        return $this->taxPositions;
    }

    /**
     * This will reverse the total
     */
    public function reverse(): void
    {
        $this->totalPriceBrutto->setNetto($this->totalPriceBrutto->getNetto() * -1);
        $this->totalPriceTax->setNetto($this->totalPriceTax->getNetto() * -1);
        $this->totalPriceNetto->setNetto($this->totalPriceNetto->getNetto() * -1);

        foreach ($this->taxPositions as $curTaxPosition) {
            $curTaxPosition->reverse();
        }
    }
}