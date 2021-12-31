<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Tests;

use domipoppe\sharkpay\Currency\EUR;
use domipoppe\sharkpay\Price;
use domipoppe\sharkpay\Tax\Tax;
use PHPUnit\Framework\TestCase;

/**
 * Class PriceTest
 *
 * @package domipoppe\sharkpay\Tests
 */
class PriceTest extends TestCase
{
    /**
     * @dataProvider dataProviderPrices
     *
     * @param float  $netto
     * @param float  $expectedBrutto
     * @param float  $expectedNetto
     * @param float  $expectedTax
     * @param float  $expectedNettoSubUnit
     * @param float  $expectedBruttoSubUnit
     * @param string $expectedNettoString
     * @param string $expectedBruttoString
     * @param string $expectedSubUnitBruttoString
     * @param string $expectedHighPrecisionBruttoString
     *
     * @covers       \domipoppe\sharkpay\Price
     */
    public function testPrices(
        float $netto,
        float $expectedBrutto,
        float $expectedNetto,
        float $expectedTax,
        float $expectedNettoSubUnit,
        float $expectedBruttoSubUnit,
        string $expectedNettoString,
        string $expectedBruttoString,
        string $expectedSubUnitBruttoString,
        string $expectedHighPrecisionBruttoString
    ): void {
        $price = new Price($netto, new EUR(), new Tax());

        $this->assertEquals($expectedBrutto, $price->getBrutto());
        $this->assertEquals($expectedNetto, $price->getNetto());
        $this->assertEquals($expectedTax, $price->getTaxAmount());
        $this->assertEquals($expectedNettoSubUnit, $price->getNettoInSubUnit());
        $this->assertEquals($expectedBruttoSubUnit, $price->getBruttoInSubUnit());
        $this->assertEquals($expectedNettoString, $price->getNettoAsString());
        $this->assertEquals($expectedBruttoString, $price->getBruttoAsString());
        $this->assertEquals($expectedSubUnitBruttoString, $price->getBruttoAsSubUnitString());
        $this->assertEquals($expectedHighPrecisionBruttoString, $price->getBruttoAsString(4));
    }

    /**
     * @return array[]
     */
    public function dataProviderPrices(): array
    {
        return [
            '#0 Data Set: 10€ Netto - 11.90€ Brutto - 1.90€ Tax' => [
                10,
                11.90,
                10,
                1.90,
                1000,
                1190,
                '10,00 €',
                '11,90 €',
                '1.190,00 ct',
                '11,9000 €'
            ],
            '#1 Data Set: 9.34€ Netto - 11.11€ Brutto - 1.77€ Tax' => [
                9.34,
                11.11,
                9.34,
                1.77,
                934,
                1111,
                '9,34 €',
                '11,11 €',
                '1.111,00 ct',
                '11,1100 €'
            ],
            '#2 Data Set: 2.10€ Netto - 2.50€ Brutto - 0.40€ Tax' => [
                2.10,
                2.50,
                2.10,
                0.40,
                210,
                250,
                '2,10 €',
                '2,50 €',
                '250,00 ct',
                '2,5000 €'
            ]
        ];
    }
}
