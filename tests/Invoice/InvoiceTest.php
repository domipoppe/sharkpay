<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Tests\Invoice;

use domipoppe\sharkpay\Currency\EUR;
use domipoppe\sharkpay\Discount;
use domipoppe\sharkpay\Exception\MixedCurrenciesException;
use domipoppe\sharkpay\Exception\TaxKeyMixedRatesException;
use domipoppe\sharkpay\Exception\UnknownDiscountTypeException;
use domipoppe\sharkpay\GEO\Address;
use domipoppe\sharkpay\GEO\Country;
use domipoppe\sharkpay\Invoice;
use domipoppe\sharkpay\Order;
use domipoppe\sharkpay\Position;
use domipoppe\sharkpay\Price;
use domipoppe\sharkpay\Tax\Tax;
use PHPUnit\Framework\TestCase;

/**
 * Class InvoiceTest
 *
 * @package domipoppe\sharkpay\Tests
 */
class InvoiceTest extends TestCase
{
    /**
     * @throws MixedCurrenciesException
     * @throws UnknownDiscountTypeException
     * @throws TaxKeyMixedRatesException
     *
     * @covers \domipoppe\sharkpay\invoice\Invoice::generateInvoice
     */
    public function testGenerateInvoice(): void
    {
        $discount = new Discount(Discount::DISCOUNT_TYPE_AMOUNT, 2, '2€ Willkommensrabatt');

        $order = new Order();
        $order->addDiscount($discount);

        $price1 = new Price(10, new EUR(), new Tax());
        $position1 = new Position($price1, 5);

        $price2 = new Price(5, new EUR(), new Tax());
        $position2 = new Position($price2, 1);

        $order->addPosition($position1);
        $order->addPosition($position2);

        $billingCountry = new Country('DE', 'Germany', '279');
        $billingAddress = new Address($billingCountry, 'Max Muster', 'Musterstadt', 'Bayern', '92852', 'Zum Tor 5');
        $address =
            new Address($billingCountry, 'Meine tolle Firma', 'Meinestadt', 'Bayern', '95822', 'Wunder 2, Gebäude 20');

        $createdAt = Invoice\CreatedAt::getCreatedAtByDateTime(new \DateTime());
        $payableAt = Invoice\PayableAt::getPayableAtInDays(20);
        $invoice = Invoice\Invoice::generateInvoice($order, $createdAt, $payableAt, $address, $billingAddress);

        $this->assertEquals(53, $invoice->getTotal()->getNetto());
        $this->assertEquals(10.07, $invoice->getTotal()->getTax());
        $this->assertEquals(63.07, $invoice->getTotal()->getBrutto());
        $this->assertEquals(10.45, $invoice->getTotal()->getTaxPositions()['DE19']->getAmount());
        $this->assertCount(2, $invoice->getTotal()->getTaxPositions());
        $this->assertEquals([0 => $discount], $invoice->getTotal()->getDiscountPositions());
        $this->assertEquals('63,07 €', $invoice->getTotal()->getBruttoAsString());
        $this->assertEquals($billingAddress, $invoice->getDeliveryAddress());
        $this->assertEquals($address, $invoice->getAddress());
        $this->assertEquals('Max Muster', $invoice->getDeliveryAddress()->getName());
        $this->assertEquals($payableAt, $invoice->getPayableAt());
        $this->assertEquals($createdAt, $invoice->getCreatedAt());
        $this->assertEquals(1, $invoice->getPositions()[0]->getNumber());
        $this->assertEquals(2, $invoice->getPositions()[1]->getNumber());
    }
}
