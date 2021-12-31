<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Tests;

use domipoppe\sharkpay\Discount;
use PHPUnit\Framework\TestCase;

/**
 * Class DiscountTest
 *
 * @package domipoppe\sharkpay\Tests
 */
class DiscountTest extends TestCase
{
    /**
     * @dataProvider dataProviderGetDiscount
     *
     * @param int $nettoAmount
     * @param float $discountValue
     * @param string $discountType
     * @param float $expectedDiscount
     * @throws \Exception
     *
     * @covers \domipoppe\sharkpay\Discount::getDiscount
     */
    public function testGetDiscount(int $nettoAmount, float $discountValue, string $discountType, float $expectedDiscount): void
    {
        $discount = new Discount($discountType, $discountValue, 'Test');

        $this->assertEquals($expectedDiscount, $discount->getDiscount($nettoAmount));
    }

    /**
     * @return array[]
     */
    public function dataProviderGetDiscount(): array
    {
        return [
            '#0 Data Set: 10€ Netto, 10% Discount | Expect 1.00€ Discount Value' => [
                10,
                10,
                Discount::DISCOUNT_TYPE_PERCENTAGE,
                1.00
            ],
            '#1 Data Set: 4210€ Netto, 105% Discount | Expect 1.00€ Discount Value' => [
                4210,
                105,
                Discount::DISCOUNT_TYPE_PERCENTAGE,
                4420.50
            ],
            '#2 Data Set: 10€ Netto, 5€ Discount | Expect 5.00€ Discount Value' => [
                10,
                5,
                Discount::DISCOUNT_TYPE_AMOUNT,
                5.00
            ],
        ];
    }
}
