<?php

declare(strict_types=1);

namespace domipoppe\sharkpay;

/**
 * Class Tax
 *
 * Default: Germany 19% VAT [DE19]
 *
 * @package domipoppe\sharkpay
 */
class Tax
{
    private string $key;
    private float $rate;
    private string $description;

    /**
     * @param string $key
     * @param float $rate
     * @param string $description
     */
    public function __construct(string $key = 'DE19', float $rate = 19, string $description = 'VAT 19%')
    {
        $this->key = $key;
        $this->rate = $rate;
        $this->description = $description;
    }

    /**
     * Will return the tax in mainUnit format
     *
     * Example Return: 0.38 if you give amount 2.00 (0.38 € tax when 2.00 € netto given)
     *
     * @param float $amount
     * @return float
     */
    public function getTaxFromAmount(float $amount): float
    {
        if ($this->rate === 0.00) {
            return 0.00;
        }

        return round(($amount * $this->rate) / 100, Price::CALCULATION_PRECISION);
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return float|int
     */
    public function getRate(): float
    {
        return $this->rate;
    }
}