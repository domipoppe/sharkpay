<?php

declare(strict_types=1);

namespace domipoppe\sharkpay;

/**
 * Class Price
 *
 * @package domipoppe\sharkpay
 */
class Price
{
    public const CALCULATION_PRECISION = 2;

    private float $netto;
    private Currency\Currency $currency;
    private Tax\Tax $tax;

    /**
     * @param float             $netto
     * @param Currency\Currency $currency
     * @param Tax\Tax           $tax
     */
    public function __construct(float $netto, Currency\Currency $currency, Tax\Tax $tax)
    {
        $this->netto = $netto;
        $this->currency = $currency;
        $this->tax = $tax;
    }

    /**
     * @param float $netto
     *
     * @return $this
     */
    public function setNetto(float $netto): self
    {
        $this->netto = $netto;
        return $this;
    }

    /**
     * @return float
     */
    public function getNetto(): float
    {
        return $this->netto;
    }

    /**
     * @return float
     */
    public function getBrutto(): float
    {
        $tax = $this->tax->getTaxFromAmount($this->getNetto());
        return round($this->getNetto() + $tax, self::CALCULATION_PRECISION);
    }

    /**
     * @return float
     */
    public function getTaxAmount(): float
    {
        return $this->tax->getTaxFromAmount($this->getNetto());
    }

    /**
     * @param int  $displayPrecision
     * @param bool $symbol
     *
     * @return string
     */
    public function getNettoAsString(int $displayPrecision = 2, bool $symbol = true): string
    {
        return $this->currency->getMainUnitString($this->getNetto(), $displayPrecision, $symbol);
    }

    /**
     * @param int  $displayPrecision
     * @param bool $symbol
     *
     * @return string
     */
    public function getNettoAsSubUnitString(int $displayPrecision = 2, bool $symbol = true): string
    {
        return $this->currency->getSubUnitString($this->getNetto(), $displayPrecision, $symbol);
    }

    /**
     * @return float
     */
    public function getNettoInSubUnit(): float
    {
        return $this->currency->getInSubUnit($this->getNetto());
    }

    /**
     * @param int  $displayPrecision
     * @param bool $symbol
     *
     * @return string
     */
    public function getTaxAsString(int $displayPrecision = 2, bool $symbol = true): string
    {
        return $this->currency->getMainUnitString($this->getTaxAmount(), $displayPrecision, $symbol);
    }

    /**
     * @param int  $displayPrecision
     * @param bool $symbol
     *
     * @return string
     */
    public function getTaxAsSubUnitString(int $displayPrecision = 2, bool $symbol = true): string
    {
        return $this->currency->getSubUnitString($this->getTaxAmount(), $displayPrecision, $symbol);
    }

    /**
     * @param int  $displayPrecision
     * @param bool $symbol
     *
     * @return string
     */
    public function getBruttoAsString(int $displayPrecision = 2, bool $symbol = true): string
    {
        return $this->currency->getMainUnitString($this->getBrutto(), $displayPrecision, $symbol);
    }

    /**
     * @param int  $displayPrecision
     * @param bool $symbol
     *
     * @return string
     */
    public function getBruttoAsSubUnitString(int $displayPrecision = 2, bool $symbol = true): string
    {
        return $this->currency->getSubUnitString($this->getBrutto(), $displayPrecision, $symbol);
    }

    /**
     * @return float
     */
    public function getBruttoInSubUnit(): float
    {
        return $this->currency->getInSubUnit($this->getBrutto());
    }

    /**
     * @return Currency\Currency
     */
    public function getCurrency(): Currency\Currency
    {
        return $this->currency;
    }

    /**
     * @param Currency\Currency $currency
     */
    public function setCurrency(Currency\Currency $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return Tax\Tax
     */
    public function getTax(): Tax\Tax
    {
        return $this->tax;
    }
}