<?php

declare(strict_types=1);

namespace domipoppe\sharkpay;

use domipoppe\sharkpay\Exception\MixedCurrenciesException;
use domipoppe\sharkpay\Exception\OrderAlreadyCalculatedException;
use domipoppe\sharkpay\Exception\OrderNotCalculatedException;
use domipoppe\sharkpay\Exception\TaxKeyMixedRatesException;
use domipoppe\sharkpay\Exception\UnknownDiscountTypeException;
use domipoppe\sharkpay\Total\TotalCalculator;

/**
 * Class Order
 *
 * @package domipoppe\sharkpay
 */
class Order
{
    private ?Total\Total $total = null;

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
            $curPosition->getSinglePrice()->setCurrency($currency);
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
     *
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
     *
     * @return $this
     */
    public function addDiscount(Discount $discount): self
    {
        $this->discounts[] = $discount;
        return $this;
    }

    /**
     * @param Discount[] $discounts
     *
     * @return $this
     */
    public function setDiscounts(array $discounts): self
    {
        $this->discounts = $discounts;
        return $this;
    }

    /**
     * @throws MixedCurrenciesException
     * @throws TaxKeyMixedRatesException
     * @throws UnknownDiscountTypeException
     * @throws OrderAlreadyCalculatedException
     */
    public function calculate(): void
    {
        if ($this->isCalculated()) {
            throw new OrderAlreadyCalculatedException();
        }

        $this->total = TotalCalculator::getTotal($this);
    }

    /**
     * @return bool
     */
    public function isCalculated(): bool
    {
        return $this->total instanceof Total\Total;
    }

    /**
     * @return Total\Total
     * @throws OrderNotCalculatedException
     */
    public function getTotal(): Total\Total
    {
        if (!$this->isCalculated() || $this->total === null) {
            throw new OrderNotCalculatedException();
        }

        return $this->total;
    }
}