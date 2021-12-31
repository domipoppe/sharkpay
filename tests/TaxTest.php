<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Tests;

use domipoppe\sharkpay\Tax\Tax;
use PHPUnit\Framework\TestCase;

/**
 * Class TaxTest
 *
 * @package domipoppe\sharkpay\Tests
 */
class TaxTest extends TestCase
{
    /**
     * @dataProvider dataProviderGetTaxFromAmount
     *
     * @param float $amount
     * @param float $expected
     *
     * @covers       \domipoppe\sharkpay\Tax\Tax::getTaxFromAmount
     */
    public function testGetTaxFromAmount(float $amount, float $expected): void
    {
        $tax = new Tax();
        $this->assertEquals($expected, $tax->getTaxFromAmount($amount));
    }

    /**
     * @return array[]
     */
    public function dataProviderGetTaxFromAmount(): array
    {
        return [
            '#0 Data Set: When 2,00 € we expect 0,38 € tax (19%)' => [
                2.00,
                0.38
            ],
            '#1 Data Set: When 0,15 € we expect 0,03 € tax (19%)' => [
                0.15,
                0.03
            ],
        ];
    }
}
