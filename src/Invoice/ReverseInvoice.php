<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Invoice;

use domipoppe\sharkpay\GEO\Address;
use domipoppe\sharkpay\Position;
use domipoppe\sharkpay\Total\Total;

/**
 * Class ReverseInvoice
 *
 * @package domipoppe\sharkpay
 */
class ReverseInvoice
{
    /** @var Position[] $positions */
    private array $positions;
    private Total $total;
    private CreatedAt $createdAt;
    private PayableAt $payableAt;
    private Address $address;
    private Address $billingAddress;
    private ?Address $deliveryAddress;

    /**
     * Will generate an reverse invoice
     *
     * @param Invoice $invoice
     *
     * @return self
     */
    public static function generate(
        Invoice $invoice
    ): self {
        return new self(
            $invoice->getCreatedAt(),
            $invoice->getPayableAt(),
            $invoice->getPositions(),
            $invoice->getTotal(),
            $invoice->getAddress(),
            $invoice->getBillingAddress(),
            $invoice->getDeliveryAddress()
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

        foreach ($positions as $curPosition) {
            $curPosition->reverse();
        }
        $total->reverse();

        $this->positions = $positions;
        $this->total = $total;
        $this->address = $address;
        $this->billingAddress = $billingAddress;
        $this->deliveryAddress = $deliveryAddress;
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