<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Tests;

use domipoppe\sharkpay\Currency\EUR;
use PHPUnit\Framework\TestCase;

/**
 * Class CurrencyTest
 *
 * @package domipoppe\sharkpay\Tests
 */
class CurrencyTest extends TestCase
{
    /**
     * @dataProvider dataProviderGetAsMainUnitString
     *
     * @param float $amount
     * @param string $expected
     *
     * @covers \domipoppe\sharkpay\Currency\Currency::getMainUnitString
     */
    public function testGetMainUnitString(float $amount, string $expected): void
    {
        $currency = new EUR();
        $this->assertEquals($currency->getMainUnitString($amount), $expected);
    }

    /**
     * @dataProvider dataProviderGetAsMainUnitStringNoSymbol
     *
     * @param float $amount
     * @param string $expected
     *
     * @covers \domipoppe\sharkpay\Currency\Currency::getMainUnitString
     */
    public function testGetMainUnitStringNoSymbol(float $amount, string $expected): void
    {
        $currency = new EUR();
        $this->assertEquals($currency->getMainUnitString($amount, 2, false), $expected);
    }

    /**
     * @dataProvider dataProviderInSubUnit
     *
     * @param float $amount
     * @param float $expected
     *
     * @covers \domipoppe\sharkpay\Currency\Currency::getInSubUnit
     */
    public function testGetInSubUnit(float $amount, float $expected): void
    {
        $currency = new EUR();
        $this->assertEquals($currency->getInSubUnit($amount), $expected);
    }

    /**
     * @dataProvider dataProviderGetAsSubUnitString
     *
     * @param float $amount
     * @param string $expected
     *
     * @covers \domipoppe\sharkpay\Currency\Currency::getSubUnitString
     */
    public function testGetSubUnitString(float $amount, string $expected): void
    {
        $currency = new EUR();
        $this->assertEquals($currency->getSubUnitString($amount), $expected);
    }

    /**
     * @dataProvider dataProviderGetAsSubUnitStringNoSymbol
     *
     * @param float $amount
     * @param string $expected
     *
     * @covers \domipoppe\sharkpay\Currency\Currency::getSubUnitString
     */
    public function testGetSubUnitStringNoSymbol(float $amount, string $expected): void
    {
        $currency = new EUR();
        $this->assertEquals($currency->getSubUnitString($amount, 2, false), $expected);
    }

    /**
     * @return array[]
     */
    public function dataProviderGetAsMainUnitString(): array
    {
        return [
            '#0 Data Set: 10 should return 10,00 €' => [
                10,
                '10,00 €'
            ],
            '#1 Data Set: 1000 should return 1.000,00 €' => [
                1000,
                '1.000,00 €'
            ],
            '#2 Data Set: 0.50 should return 0,50 €' => [
                0.50,
                '0,50 €'
            ],
            '#3 Data Set: 100.50 should return 100,50 €' => [
                100.50,
                '100,50 €'
            ]
        ];
    }

    /**
     * @return array[]
     */
    public function dataProviderGetAsMainUnitStringNoSymbol(): array
    {
        return [
            '#0 Data Set: 10 should return 10,00 €' => [
                10,
                '10,00 Euro'
            ],
            '#1 Data Set: 1000 should return 1.000,00 €' => [
                1000,
                '1.000,00 Euro'
            ],
            '#2 Data Set: 0.50 should return 0,50 €' => [
                0.50,
                '0,50 Euro'
            ],
            '#3 Data Set: 100.50 should return 100,50 €' => [
                100.50,
                '100,50 Euro'
            ]
        ];
    }

    /**
     * @return \int[][]
     */
    public function dataProviderInSubUnit(): array
    {
        return [
            '#0 Data Set: 10.00€ should return 1000 (ct)' => [
                10.00,
                1000
            ],
            '#1 Data Set: 5.50€ should return 550 (ct)' => [
                5.50,
                550
            ]
        ];
    }

    /**
     * @return array[]
     */
    public function dataProviderGetAsSubUnitString(): array
    {
        return [
            '#0 Data Set: 5.10€ should return 510 ct' => [
                5.10,
                '510,00 ct'
            ],
            '#1 Data Set: 1000€ should return 100000 ct' => [
                1000,
                '100.000,00 ct'
            ],
            '#2 Data Set: 0.50€ should return 50 ct' => [
                0.50,
                '50,00 ct'
            ]
        ];
    }

    /**
     * @return array[]
     */
    public function dataProviderGetAsSubUnitStringNoSymbol(): array
    {
        return [
            '#0 Data Set: 5.10€ should return 510 ct' => [
                5.10,
                '510,00 Cent'
            ],
            '#1 Data Set: 1000€ should return 100000 ct' => [
                1000,
                '100.000,00 Cent'
            ],
            '#2 Data Set: 0.50€ should return 50 ct' => [
                0.50,
                '50,00 Cent'
            ]
        ];
    }
}
