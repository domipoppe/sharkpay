<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Tests\Invoice;

use domipoppe\sharkpay\Currency\EUR;
use domipoppe\sharkpay\Discount;
use domipoppe\sharkpay\GEO\Address;
use domipoppe\sharkpay\GEO\Country;
use domipoppe\sharkpay\Invoice\CreatedAt;
use domipoppe\sharkpay\Invoice\Invoice;
use domipoppe\sharkpay\Invoice\PayableAt;
use domipoppe\sharkpay\Invoice\ReverseInvoice;
use domipoppe\sharkpay\Order;
use domipoppe\sharkpay\Position;
use domipoppe\sharkpay\Price;
use domipoppe\sharkpay\Tax\Tax;
use PHPUnit\Framework\TestCase;

/**
 * Class ReverseInvoiceTest
 *
 * @package domipoppe\sharkpay\Tests\Invoice
 */
class ReverseInvoiceTest extends TestCase
{
    /**
     * @covers \domipoppe\sharkpay\Invoice\ReverseInvoice
     */
    public function testReverseInvoice(): void
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

        $createdAt = CreatedAt::getCreatedAtByDateTime(new \DateTime());
        $payableAt = PayableAt::getPayableAtInDays(20);

        $order->calculate();

        $invoice = Invoice::generateInvoice($order, $createdAt, $payableAt, $address, $billingAddress);
        $this->assertEquals(53, $invoice->getTotal()->getNetto());
        $this->assertEquals(10.07, $invoice->getTotal()->getTax());
        $this->assertEquals(63.07, $invoice->getTotal()->getBrutto());

        $reverseInvoice = ReverseInvoice::generateReverseInvoice($invoice);
        $this->assertEquals(-53, $reverseInvoice->getTotal()->getNetto());
        $this->assertEquals(-10.07, $reverseInvoice->getTotal()->getTax());
        $this->assertEquals(-63.07, $reverseInvoice->getTotal()->getBrutto());
    }
}
