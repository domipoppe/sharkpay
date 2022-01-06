<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Tax;

/**
 * Class TaxPosition
 *
 * Is required for the TotalTaxHandler. It stores total tax for one tax rate/key.
 *
 * @package domipoppe\sharkpay
 */
class TaxPosition
{
    private float $rate;
    private float $amount = 0.00;
    private string $key;

    public function __construct(string $key, float $rate)
    {
        $this->key = $key;
        $this->rate = $rate;
    }

    /**
     * @param float $amount
     */
    public function addAmount(float $amount): void
    {
        $this->amount += $amount;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }

    /**
     * This will reverse the tax position
     */
    public function reverse(): void
    {
        $this->setAmount($this->getAmount() * -1);
    }
}