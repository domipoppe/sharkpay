<?php

declare(strict_types=1);

namespace domipoppe\sharkpay;

/**
 * Class Position
 *
 * @package domipoppe\sharkpay
 */
class Position
{
    private Price $price;
    private float $quantity;
    private string $identifier = '';
    private string $name = '';
    private string $description = '';
    private array $data = [];

    /**
     * @param Price $price the price of this position
     * @param float $quantity the quantity of this position (default: 1)
     */
    public function __construct(Price $price, float $quantity = 1.00)
    {
        $this->price = $price;
        $this->quantity = $quantity;
    }

    /**
     * Will return the total brutto of this position
     *
     * @return float
     */
    public function getTotalBrutto(): float
    {
        return round($this->getPrice()->getBrutto() * $this->getQuantity(), Price::CALCULATION_PRECISION);
    }

    /**
     * Will return the total brutto of this position
     *
     * @return float
     */
    public function getTotalNetto(): float
    {
        return round($this->getPrice()->getNetto() * $this->getQuantity(), Price::CALCULATION_PRECISION);
    }

    /**
     * Will return the total tax of this position
     *
     * @return float
     */
    public function getTotalTax(): float
    {
        return round($this->getPrice()->getTaxAmount() * $this->getQuantity(), Price::CALCULATION_PRECISION);
    }

    /**
     * @return float
     */
    public function getTaxRate(): float
    {
        return $this->getPrice()->getTax()->getRate();
    }

    /**
     * @return Price
     */
    public function getPrice(): Price
    {
        return $this->price;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     * @return Position
     */
    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * @return Position
     */
    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Position
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Position
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return Position
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }
}