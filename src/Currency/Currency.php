<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Currency;

use domipoppe\sharkpay\Price;

/**
 * Class Currency
 *
 * This class is used to handle currency
 *
 * ISOCode based on ISO 4217
 *
 * @package domipoppe\sharkpay\Currencies
 */
abstract class Currency
{
    private string $thousandsSeparator;
    private string $decimalSeparator;
    private string $isoCode;
    private string $mainUnit;
    private string $mainUnitSymbol;
    private string $subUnit;
    private string $subUnitSymbol;
    private int $subUnitDivider;

    /**
     * @param string $thousandsSeparator
     * @param string $decimalSeparator
     * @param string $isoCode
     * @param string $mainUnit
     * @param string $mainUnitSymbol
     * @param string $subUnit
     * @param string $subUnitSymbol
     * @param int $subUnitDivider
     */
    public function __construct(
        string $thousandsSeparator,
        string $decimalSeparator,
        string $isoCode,
        string $mainUnit,
        string $mainUnitSymbol,
        string $subUnit,
        string $subUnitSymbol,
        int    $subUnitDivider)
    {
        $this->thousandsSeparator = $thousandsSeparator;
        $this->decimalSeparator = $decimalSeparator;
        $this->isoCode = $isoCode;
        $this->mainUnit = $mainUnit;
        $this->mainUnitSymbol = $mainUnitSymbol;
        $this->subUnit = $subUnit;
        $this->subUnitSymbol = $subUnitSymbol;
        $this->subUnitDivider = $subUnitDivider;
    }

    /**
     * Formatter for displaying an amount in the currencies main unit
     *
     * @param float $amount
     * @param int $displayPrecision
     * @param bool $symbol
     * @return string
     */
    public function getMainUnitString(float $amount, int $displayPrecision = 2, bool $symbol = true): string
    {
        $formattedValue = number_format($amount, $displayPrecision, $this->decimalSeparator, $this->thousandsSeparator);
        return sprintf('%s %s', $formattedValue, $symbol ? $this->mainUnitSymbol : $this->mainUnit);
    }

    /**
     * Formatter to get the currency in it's sub-unit
     *
     * @param float $amount
     * @return float
     */
    public function getInSubUnit(float $amount): float
    {
        return round($amount * $this->subUnitDivider, Price::CALCULATION_PRECISION);
    }

    /**
     * Formatter for displaying an amount in the currencies sub-unit
     *
     * @param float $amount
     * @param int $displayPrecision
     * @param bool $symbol
     * @return string
     */
    public function getSubUnitString(float $amount, int $displayPrecision = 2, bool $symbol = true): string
    {
        $formattedValue = number_format($this->getInSubUnit($amount), $displayPrecision, $this->decimalSeparator, $this->thousandsSeparator);
        return sprintf('%s %s', $formattedValue, $symbol ? $this->subUnitSymbol : $this->subUnit);
    }

    /**
     * @return string
     */
    public function getIsoCode(): string
    {
        return $this->isoCode;
    }
}