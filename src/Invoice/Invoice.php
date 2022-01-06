<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Invoice;

use domipoppe\sharkpay\Exception\OrderNotCalculatedException;
use domipoppe\sharkpay\GEO\Address;
use domipoppe\sharkpay\Order;
use domipoppe\sharkpay\Position;
use domipoppe\sharkpay\Total\Total;

/**
 * Class Invoice
 *
 * @package domipoppe\sharkpay
 */
class Invoice
{
    /** @var Position[] $positions */
    private array $positions;
    private int $curPositionNumber = 1;
    private Total $total;
    private CreatedAt $createdAt;
    private PayableAt $payableAt;
    private Address $address;
    private Address $billingAddress;
    private ?Address $deliveryAddress;

    /**
     * Will generate an invoice
     *
     * @param Order        $order
     * @param CreatedAt    $createdAt
     * @param PayableAt    $payableAt
     * @param Address      $address
     * @param Address      $billingAddress
     * @param Address|null $deliveryAddress
     *
     * @return self
     * @throws OrderNotCalculatedException
     */
    public static function generate(
        Order $order,
        CreatedAt $createdAt,
        PayableAt $payableAt,
        Address $address,
        Address $billingAddress,
        ?Address $deliveryAddress = null
    ): self {
        if (!$order->isCalculated()) {
            throw new OrderNotCalculatedException();
        }

        return new self(
            $createdAt,
            $payableAt,
            $order->getPositions(),
            $order->getTotal(),
            $address,
            $billingAddress,
            $deliveryAddress
        );
    }

    /**
     * @param CreatedAt    $createdAt
     * @param PayableAt    $payableAt
     * @param Position[]   $positions
     * @param Total        $total
     * @param Address      $address
     * @param Address      $billingAddress
     * @param Address|null $deliveryAddress
     */
    public function __construct(
        CreatedAt $createdAt,
        PayableAt $payableAt,
        array $positions,
        Total $total,
        Address $address,
        Address $billingAddress,
        ?Address $deliveryAddress = null
    ) {
        $this->createdAt = $createdAt;
        $this->payableAt = $payableAt;
        $this->positions = $this->setPositionNumbers($positions);
        $this->total = $total;
        $this->address = $address;
        $this->billingAddress = $billingAddress;
        $this->deliveryAddress = $deliveryAddress;
    }

    /**
     * @param Position[] $positions
     *
     * @return Position[]
     */
    public function setPositionNumbers(array $positions): array
    {
        foreach ($positions as $curPosition) {
            $curPosition->setNumber($this->curPositionNumber);
            $this->curPositionNumber++;
        }
        return $positions;
    }

    /**
     * @return Position[]
     */
    public function getPositions(): array
    {
        return $this->positions;
    }

    /**
     * @return Total
     */
    public function getTotal(): Total
    {
        return $this->total;
    }

    /**
     * @return Address
     */
    public function getBillingAddress(): Address
    {
        return $this->billingAddress;
    }

    /**
     * @param Address $billingAddress
     */
    public function setBillingAddress(Address $billingAddress): void
    {
        $this->billingAddress = $billingAddress;
    }

    /**
     * Will return billing address if delivery address is not set
     *
     * @return Address
     */
    public function getDeliveryAddress(): Address
    {
        return $this->deliveryAddress ?? $this->billingAddress;
    }

    /**
     * @param Address|null $deliveryAddress
     */
    public function setDeliveryAddress(?Address $deliveryAddress): void
    {
        $this->deliveryAddress = $deliveryAddress;
    }

    /**
     * @return PayableAt
     */
    public function getPayableAt(): PayableAt
    {
        return $this->payableAt;
    }

    /**
     * @param PayableAt $payableAt
     */
    public function setPayableAt(PayableAt $payableAt): void
    {
        $this->payableAt = $payableAt;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    /**
     * @return CreatedAt
     */
    public function getCreatedAt(): CreatedAt
    {
        return $this->createdAt;
    }

    /**
     * @param CreatedAt $createdAt
     */
    public function setCreatedAt(CreatedAt $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}