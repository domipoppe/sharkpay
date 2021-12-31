<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Tests;

use domipoppe\sharkpay\Currency\EUR;
use domipoppe\sharkpay\Currency\USD;
use domipoppe\sharkpay\Exception\MixedCurrenciesException;
use domipoppe\sharkpay\Exception\TaxKeyMixedRatesException;
use domipoppe\sharkpay\Order;
use domipoppe\sharkpay\Discount;
use domipoppe\sharkpay\Position;
use domipoppe\sharkpay\Price;
use domipoppe\sharkpay\Tax\Tax;
use PHPUnit\Framework\TestCase;

/**
 * Class OrderTest
 *
 * @package domipoppe\sharkpay\Tests
 */
class OrderTest extends TestCase
{
    /**
     * @covers \domipoppe\sharkpay\Order::getTotal
     */
    public function testOrderMixedCurrenciesException(): void
    {
        $this->expectException(MixedCurrenciesException::class);

        $order = new Order();

        $price1 = new Price(10.50, new EUR(), new Tax());
        $position1 = new Position($price1, 5);

        $price2 = new Price(5, new EUR(), new Tax());
        $position2 = new Position($price2, 1);

        $price3 = new Price(5, new USD(), new Tax());
        $position3 = new Position($price3, 1);

        $order->addPosition($position1);
        $order->addPosition($position2);
        $order->addPosition($position3);

        $total = $order->getTotal();
    }

    /**
     * @covers \domipoppe\sharkpay\Order::getTotal
     */
    public function testOrderTaxKeyMixedRatesException(): void
    {
        $this->expectException(TaxKeyMixedRatesException::class);

        $order = new Order();

        $price1 = new Price(10.50, new EUR(), new Tax('DE19', 19, 'Deutsche Umsatzsteuer'));
        $position1 = new Position($price1, 5);

        $price2 = new Price(5, new EUR(), new Tax('DE19', 16, 'Deutsche Steuer reduziert'));
        $position2 = new Position($price2, 1);

        $order->addPosition($position1);
        $order->addPosition($position2);

        $total = $order->getTotal();
    }

    /**
     * @throws \Exception
     *
     * @covers \domipoppe\sharkpay\Order::getTotal
     */
    public function testCartNoDiscountsSameTax(): void
    {
        $order = new Order();

        $price1 = new Price(10.50, new EUR(), new Tax());
        $position1 = new Position($price1, 5);

        $price2 = new Price(5, new EUR(), new Tax());
        $position2 = new Position($price2, 1);

        $price3 = new Price(20, new EUR(), new Tax());
        $position3 = new Position($price3, 1);

        $order->addPosition($position1);
        $order->addPosition($position2);
        $order->addPosition($position3);

        $total = $order->getTotal();

        $this->assertCount(1, $total->getTaxPositions());
        $this->assertNotEmpty($total->getTaxPositions()['DE19']);
        $this->assertEquals(14.75, $total->getTaxPositions()['DE19']->getAmount());
        $this->assertEquals(77.50, $total->getNetto());
        $this->assertEquals(14.75, $total->getTax());
        $this->assertEquals(92.25, $total->getBrutto());
        $this->assertEquals('92,25 €', $total->getBruttoAsString());
    }

    /**
     * @throws \Exception
     *
     * @covers \domipoppe\sharkpay\Order::getTotal
     */
    public function testCartNoDiscountsMixedTax(): void
    {
        $order = new Order();

        $price1 = new Price(10.50, new EUR(), new Tax());
        $position1 = new Position($price1, 5);

        $price2 = new Price(5, new EUR(), new Tax());
        $position2 = new Position($price2, 1);

        $price3 = new Price(20, new EUR(), new Tax('USA10', 10, 'USA 10%'));
        $position3 = new Position($price3, 1);

        $order->addPosition($position1);
        $order->addPosition($position2);
        $order->addPosition($position3);

        $total = $order->getTotal();

        $this->assertCount(2, $total->getTaxPositions());
        $this->assertNotEmpty($total->getTaxPositions()['DE19']);
        $this->assertNotEmpty($total->getTaxPositions()['USA10']);
        $this->assertEquals(10.95, $total->getTaxPositions()['DE19']->getAmount());
        $this->assertEquals(2.00, $total->getTaxPositions()['USA10']->getAmount());
        $this->assertEquals(77.50, $total->getNetto());
        $this->assertEquals(12.95, $total->getTax());
        $this->assertEquals(90.45, $total->getBrutto());
        $this->assertEquals('90,45 €', $total->getBruttoAsString());
    }

    /**
     * @throws \Exception
     *
     * @covers \domipoppe\sharkpay\Order::getTotal
     */
    public function testCartDiscountAmountSameTax(): void
    {
        $discount = new Discount(Discount::DISCOUNT_TYPE_AMOUNT, 2, '2€ Willkommensrabatt');

        $order = new Order();
        $order->addDiscount($discount);

        $price1 = new Price(10.50, new EUR(), new Tax());
        $position1 = new Position($price1, 5);

        $price2 = new Price(5, new EUR(), new Tax());
        $position2 = new Position($price2, 1);

        $price3 = new Price(20, new EUR(), new Tax());
        $position3 = new Position($price3, 1);

        $order->addPosition($position1);
        $order->addPosition($position2);
        $order->addPosition($position3);

        $total = $order->getTotal();

        $this->assertCount(2, $total->getTaxPositions());
        $this->assertNotEmpty($total->getTaxPositions()['DE19']);
        $this->assertEquals(14.75, $total->getTaxPositions()['DE19']->getAmount());
        $this->assertEquals(75.50, $total->getNetto());
        $this->assertEquals(14.37, $total->getTax());
        $this->assertEquals(89.87, $total->getBrutto());
        $this->assertEquals('89,87 €', $total->getBruttoAsString());
    }

    /**
     * @throws \Exception
     *
     * @covers \domipoppe\sharkpay\Order::getTotal
     */
    public function testCartDiscountAmountMixedTax(): void
    {
        $discount = new Discount(Discount::DISCOUNT_TYPE_AMOUNT, 2, '2€ Willkommensrabatt');

        $order = new Order();
        $order->addDiscount($discount);

        $price1 = new Price(10.50, new EUR(), new Tax());
        $position1 = new Position($price1, 5);

        $price2 = new Price(5, new EUR(), new Tax('USA10', 10, 'USA 10%'));
        $position2 = new Position($price2, 1);

        $price3 = new Price(20, new EUR(), new Tax());
        $position3 = new Position($price3, 1);

        $order->addPosition($position1);
        $order->addPosition($position2);
        $order->addPosition($position3);

        $total = $order->getTotal();

        $this->assertCount(3, $total->getTaxPositions());
        $this->assertNotEmpty($total->getTaxPositions()['DE19']);
        $this->assertEquals(13.80, $total->getTaxPositions()['DE19']->getAmount());
        $this->assertEquals(75.50, $total->getNetto());
        $this->assertEquals(13.98, $total->getTax());
        $this->assertEquals(89.48, $total->getBrutto());
        $this->assertEquals('89,48 €', $total->getBruttoAsString());
    }

    /**
     * @throws \Exception
     *
     * @covers \domipoppe\sharkpay\Order::getTotal
     */
    public function testCartDiscountAmountAndPercentageMixedTax(): void
    {
        $discount = new Discount(Discount::DISCOUNT_TYPE_AMOUNT, 2, '2€ Willkommensrabatt');
        $discountPercentage = new Discount(Discount::DISCOUNT_TYPE_PERCENTAGE, 5, '5% Superrabatt');

        $order = new Order();
        $order->addDiscount($discount);
        $order->addDiscount($discountPercentage);

        $price1 = new Price(10.50, new EUR(), new Tax());
        $position1 = new Position($price1, 5);

        $price2 = new Price(5, new EUR(), new Tax('USA10', 10, 'USA 10%'));
        $position2 = new Position($price2, 1);

        $price3 = new Price(20, new EUR(), new Tax());
        $position3 = new Position($price3, 1);

        $order->addPosition($position1);
        $order->addPosition($position2);
        $order->addPosition($position3);

        $total = $order->getTotal();

        $totalBrutto = 0;
        foreach ($order->getPositions() as $curPosition) {
            $totalBrutto += $curPosition->getTotalBrutto();
        }
        $this->assertEquals($totalBrutto, $total->getBrutto());

        $this->assertCount(3, $total->getTaxPositions());
        $this->assertNotEmpty($total->getTaxPositions()['DE19']);
        $this->assertEquals(13.80, $total->getTaxPositions()['DE19']->getAmount());
        $this->assertEquals(0.50, $total->getTaxPositions()['USA10']->getAmount());
        $this->assertEquals(
            -0.92,
            $total->getTaxPositions()['NONE']->getAmount()
        ); //Tax correction position due to discounts
        $this->assertEquals(-0.92, $order->getPositions()[3]->getTotalTax());
        $this->assertEquals(71.72, $total->getNetto());
        $this->assertEquals(13.38, $total->getTax());
        $this->assertEquals(85.10, $total->getBrutto());
        $this->assertEquals('85,10 €', $total->getBruttoAsString());
    }

    /**
     * @throws \Exception
     *
     * @covers \domipoppe\sharkpay\Order::getTotal
     */
    public function testCartDiscountAmountAndPercentageSameTax(): void
    {
        $discount = new Discount(Discount::DISCOUNT_TYPE_AMOUNT, 2, '2€ Willkommensrabatt');
        $discountPercentage = new Discount(Discount::DISCOUNT_TYPE_PERCENTAGE, 10, '10% Neukundenbonus');

        $order = new Order();
        $order->addDiscount($discount);
        $order->addDiscount($discountPercentage);

        $price1 = new Price(10.50, new EUR(), new Tax());
        $position1 = new Position($price1, 5);

        $price2 = new Price(5, new EUR(), new Tax());
        $position2 = new Position($price2, 1);

        $price3 = new Price(20, new EUR(), new Tax());
        $position3 = new Position($price3, 1);

        $order->addPosition($position1);
        $order->addPosition($position2);
        $order->addPosition($position3);

        $total = $order->getTotal();

        $this->assertCount(2, $total->getTaxPositions());
        $this->assertNotEmpty($total->getTaxPositions()['DE19']);
        $this->assertEquals(14.75, $total->getTaxPositions()['DE19']->getAmount());
        $this->assertEquals(67.95, $total->getNetto());
        $this->assertEquals(12.94, $total->getTax());
        $this->assertEquals(80.89, $total->getBrutto());
        $this->assertEquals('80,89 €', $total->getBruttoAsString());
    }
}
