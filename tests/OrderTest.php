<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Tests;

use domipoppe\sharkpay\Currency\EUR;
use domipoppe\sharkpay\Currency\USD;
use domipoppe\sharkpay\Exception\MixedCurrenciesException;
use domipoppe\sharkpay\Order;
use domipoppe\sharkpay\Discount;
use domipoppe\sharkpay\Position;
use domipoppe\sharkpay\Price;
use domipoppe\sharkpay\Tax;
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
        $this->assertEquals(14.725, $total->getTaxPositions()['DE19']['amount']);
        $this->assertEquals(77.50, $total->getNetto());
        $this->assertEquals(14.725, $total->getTax());
        $this->assertEquals(92.225, $total->getBrutto());
        $this->assertEquals('92,23 €', $total->getBruttoAsString());
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
        $this->assertEquals(10.925, $total->getTaxPositions()['DE19']['amount']);
        $this->assertEquals(2.00, $total->getTaxPositions()['USA10']['amount']);
        $this->assertEquals(77.50, $total->getNetto());
        $this->assertEquals(12.925, $total->getTax());
        $this->assertEquals(90.425, $total->getBrutto());
        $this->assertEquals('90,43 €', $total->getBruttoAsString());
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
        $this->assertEquals(14.725, $total->getTaxPositions()['DE19']['amount']);
        $this->assertEquals(75.50, $total->getNetto());
        $this->assertEquals(14.345, $total->getTax());
        $this->assertEquals(89.845, $total->getBrutto());
        $this->assertEquals('89,85 €', $total->getBruttoAsString());
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
        $this->assertEquals(13.775, $total->getTaxPositions()['DE19']['amount']);
        $this->assertEquals(75.50, $total->getNetto());
        $this->assertEquals(13.955, $total->getTax());
        $this->assertEquals(89.455, $total->getBrutto());
        $this->assertEquals('89,46 €', $total->getBruttoAsString());
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

        $this->assertCount(3, $total->getTaxPositions());
        $this->assertNotEmpty($total->getTaxPositions()['DE19']);
        $this->assertEquals(13.775, $total->getTaxPositions()['DE19']['amount']);
        $this->assertEquals(71.725, $total->getNetto());
        $this->assertEquals(13.351, $total->getTax());
        $this->assertEquals(85.076, $total->getBrutto());
        $this->assertEquals('85,08 €', $total->getBruttoAsString());
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
        $this->assertEquals(14.725, $total->getTaxPositions()['DE19']['amount']);
        $this->assertEquals(67.95, $total->getNetto());
        $this->assertEquals(12.9105, $total->getTax());
        $this->assertEquals(80.8605, $total->getBrutto());
        $this->assertEquals('80,86 €', $total->getBruttoAsString());
    }
}
