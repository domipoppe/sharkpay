<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Total;

use domipoppe\sharkpay\Tax\TaxPosition;

/**
 * Class TotalSummary
 *
 * Is used to temporarily store totals.
 *
 * @package domipoppe\sharkpay
 */
class TotalSummary
{
    /** @var TaxPosition[] $taxPositions */
    private array $taxPositions;
    private float $totalNetto;
    private float $totalTax;
    private float $totalBrutto;

    /**
     * @param TaxPosition[] $taxPositions
     * @param float         $totalNetto
     * @param float         $totalTax
     * @param float         $totalBrutto
     */
    public function __construct(array $taxPositions, float $totalNetto, float $totalTax, float $totalBrutto)
    {
        $this->taxPositions = $taxPositions;
        $this->totalNetto = $totalNetto;
        $this->totalTax = $totalTax;
        $this->totalBrutto = $totalBrutto;
    }

    /**
     * @return float
     */
    public function getTotalBrutto(): float
    {
        return $this->totalBrutto;
    }

    /**
     * @return float
     */
    public function getTotalTax(): float
    {
        return $this->totalTax;
    }

    /**
     * @return TaxPosition[]
     */
    public function getTaxPositions(): array
    {
        return $this->taxPositions;
    }

    /**
     * @return float
     */
    public function getTotalNetto(): float
    {
        return $this->totalNetto;
    }
}