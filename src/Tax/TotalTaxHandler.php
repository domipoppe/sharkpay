<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Tax;

use domipoppe\sharkpay\Exception\TaxKeyMixedRatesException;

/**
 * Class TotalTaxHandler
 *
 * This class handles taxes of positions. It will be able to generate TaxPositions.
 *
 * @package domipoppe\sharkpay
 */
class TotalTaxHandler
{
    /** @var TaxPosition[] $taxPositions */
    private array $taxPositions = [];

    /**
     * @param string $key
     * @param float  $rate
     *
     * @return TaxPosition
     * @throws TaxKeyMixedRatesException
     */
    public function getTax(string $key, float $rate): TaxPosition
    {
        if (!empty($this->taxPositions[$key])) {
            if ($this->taxPositions[$key]->getRate() !== $rate) {
                throw new TaxKeyMixedRatesException(
                    sprintf('Positions with the tax key "%s" have different rates!', $key)
                );
            }
            return $this->taxPositions[$key];
        }
        $this->taxPositions[$key] = new TaxPosition($key, $rate);
        return $this->taxPositions[$key];
    }

    /**
     * @return TaxPosition[]
     */
    public function getTaxPositions(): array
    {
        return $this->taxPositions;
    }

    /**
     * @param string $key
     * @param float  $amount
     * @param float  $rate
     *
     * @throws TaxKeyMixedRatesException
     */
    public function addTax(string $key, float $amount, float $rate): void
    {
        $taxPosition = $this->getTax($key, $rate);
        $taxPosition->addAmount($amount);
        $this->taxPositions[$key] = $taxPosition;
    }
}