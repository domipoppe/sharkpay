<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Tests;

use domipoppe\sharkpay\Currency\EUR;
use domipoppe\sharkpay\Position;
use domipoppe\sharkpay\Price;
use domipoppe\sharkpay\Tax\Tax;
use PHPUnit\Framework\TestCase;

/**
 * Class PositionTest
 *
 * @package domipoppe\sharkpay\Tests
 */
class PositionTest extends TestCase
{
    /**
     * @dataProvider dataProviderTestPosition
     *
     * @param float $nettoAmount
     * @param float $quantity
     * @param float $bruttoAmount
     * @param float $totalAmount
     * @param float $totalNettoAmount
     * @param float $totalTaxAmount
     *
     * @covers       \domipoppe\sharkpay\Position
     */
    public function testPosition(
        float $nettoAmount,
        float $quantity,
        float $bruttoAmount,
        float $totalAmount,
        float $totalNettoAmount,
        float $totalTaxAmount
    ): void {
        $price = new Price($nettoAmount, new EUR(), new Tax());
        $position = new Position($price, $quantity);
        $position->setName('Test Position')->setIdentifier('test');

        $this->assertEquals($quantity, $position->getQuantity());
        $this->assertEquals('piece', $position->getUnit());
        $this->assertEquals($nettoAmount, $position->getSinglePrice()->getNetto());
        $this->assertEquals($bruttoAmount, $position->getSinglePrice()->getBrutto());
        $this->assertEquals($totalAmount, $position->getTotalBrutto());
        $this->assertEquals($totalNettoAmount, $position->getTotalNetto());
        $this->assertEquals($totalTaxAmount, $position->getTotalTax());
    }

    /**
     * @return array[]
     */
    public function dataProviderTestPosition(): array
    {
        return [
            '#0 Data Set: 10€ Netto, 2 Quantity' => [
                10,
                2,
                11.90,
                23.80,
                20,
                3.80
            ],
            '#1 Data Set: 1312.51€ Netto, 4.5 Quantity' => [
                1312.51,
                4.5,
                1561.89,
                7028.51,
                5906.30,
                1122.21
            ]
        ];
    }
}
