<?php

declare(strict_types=1);

namespace domipoppe\sharkpay;

/**
 * Class Invoice
 *
 * @package domipoppe\sharkpay
 */
class Invoice
{
    /** @var Position[] $positions */
    private array $positions;
    private Total $total;

    /**
     * Will generate an invoice
     *
     * @param Order $order
     * @return self
     * @throws \Exception
     */
    public static function generateInvoice(Order $order): self
    {
        return new self($order->getPositions(), $order->getTotal());
    }

    /**
     * @param Position[] $positions
     * @param Total $total
     */
    public function __construct(array $positions, Total $total)
    {
        $this->positions = $positions;
        $this->total = $total;
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
}