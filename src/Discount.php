<?php

declare(strict_types=1);

namespace domipoppe\sharkpay;

use domipoppe\sharkpay\Exception\UnknownDiscountTypeException;

/**
 * Class Discount
 *
 * @package domipoppe\sharkpay
 */
class Discount
{
    public const DISCOUNT_TYPE_PERCENTAGE = 'percentage';
    public const DISCOUNT_TYPE_AMOUNT = 'amount';

    private string $type;
    private float $value;
    private string $description;

    /**
     * @param string $type
     * @param float $value
     * @param string $description
     */
    public function __construct(string $type, float $value, string $description)
    {
        $this->type = $type;
        $this->value = $value;
        $this->description = $description;
    }

    /**
     * Will return the discount amount for the given netto amount based upon the discounts type
     *
     * @param float $netto
     * @return float
     * @throws UnknownDiscountTypeException
     */
    public function getDiscount(float $netto): float
    {
        switch ($this->type) {
            case static::DISCOUNT_TYPE_AMOUNT:
                return round($this->value, Price::CALCULATION_PRECISION);

            case static::DISCOUNT_TYPE_PERCENTAGE:
                return round($netto - ($netto * ((100 - $this->value) / 100)), Price::CALCULATION_PRECISION);

            default:
                throw new UnknownDiscountTypeException(sprintf('Discount type "%s" is not known!', $this->type));
        }
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}