<?php

declare(strict_types=1);

namespace domipoppe\sharkpay;

use domipoppe\sharkpay\Exception\MixedCurrenciesException;
use domipoppe\sharkpay\Exception\UnknownDiscountTypeException;

/**
 * Class Order
 *
 * @package domipoppe\sharkpay
 */
class Order
{
    /** @var Position[] $positions */
    private array $positions = [];
    /** @var Discount[] $discounts */
    private array $discounts = [];

    /**
     * Will overwrite the currency of all positions
     *
     * @param Currency\Currency $currency
     */
    public function setCurrencyToAllPositions(Currency\Currency $currency): void
    {
        foreach ($this->getPositions() as $curPosition) {
            $curPosition->getPrice()->setCurrency($currency);
        }
    }

    /**
     * @return Position[]
     */
    public function getPositions(): array
    {
        return $this->positions;
    }

    /**
     * @param Position $position
     * @return $this
     */
    public function addPosition(Position $position): self
    {
        $this->positions[] = $position;
        return $this;
    }

    /**
     * @param Position[] $positions
     */
    public function setPositions(array $positions): self
    {
        $this->positions = $positions;
        return $this;
    }

    /**
     * @return Discount[]
     */
    public function getDiscounts(): array
    {
        return $this->discounts;
    }

    /**
     * @param Discount $discount
     * @return $this
     */
    public function addDiscount(Discount $discount): self
    {
        $this->discounts[] = $discount;
        return $this;
    }

    /**
     * @param Discount[] $discounts
     * @return $this
     */
    public function setDiscounts(array $discounts): self
    {
        $this->discounts = $discounts;
        return $this;
    }

    /**
     * @return Total
     * @throws MixedCurrenciesException
     * @throws UnknownDiscountTypeException
     */
    public function getTotal(): Total
    {
        return TotalCalculator::getTotal($this);
    }
}