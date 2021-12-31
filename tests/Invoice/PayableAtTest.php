<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Tests\Invoice;

use domipoppe\sharkpay\Invoice\PayableAt;
use PHPUnit\Framework\TestCase;

/**
 * Class PayableAtTest
 *
 * @package domipoppe\sharkpay\Tests\Invoice
 */
class PayableAtTest extends TestCase
{
    /**
     * @covers \domipoppe\sharkpay\Invoice\PayableAt::getPayableAtByDateTime
     */
    public function testGetPayableAtByDateTime(): void
    {
        $dateTime = new \DateTime();
        $payableAt = PayableAt::getPayableAtByDateTime($dateTime);
        $this->assertEquals($dateTime, $payableAt->getDateTime());
    }

    /**
     * @covers \domipoppe\sharkpay\Invoice\PayableAt::getPayableAtNow
     */
    public function testGetPayableAtNow(): void
    {
        $dateTime = new \DateTime();
        $payableAt = PayableAt::getPayableAtNow();
        $this->assertEquals($dateTime->format('Y-m-d'), $payableAt->getInternationalFormat());
    }

    /**
     * @covers \domipoppe\sharkpay\Invoice\PayableAt::getPayableAtInDays
     */
    public function testGetPayableAtInDays(): void
    {
        $dateTime = new \DateTime();
        $dateTime->modify('+20 day');
        $payableAt = PayableAt::getPayableAtInDays(20);
        $this->assertEquals($dateTime->format('Y-m-d'), $payableAt->getDateTime()->format('Y-m-d'));
    }

    /**
     * @covers \domipoppe\sharkpay\Invoice\PayableAt::getGermanFormat
     */
    public function testGetGermanFormat(): void
    {
        $dateTime = new \DateTime('2022-12-20');
        $payableAt = PayableAt::getPayableAtByDateTime($dateTime);
        $this->assertEquals('20.12.2022', $payableAt->getGermanFormat());
    }

    /**
     * @covers \domipoppe\sharkpay\Invoice\PayableAt::getInternationalFormat
     */
    public function testGetInternationalFormat(): void
    {
        $dateTime = new \DateTime('2022-12-20');
        $payableAt = PayableAt::getPayableAtByDateTime($dateTime);
        $this->assertEquals('2022-12-20', $payableAt->getInternationalFormat());
    }

    /**
     * @covers \domipoppe\sharkpay\Invoice\PayableAt::getDay
     * @covers \domipoppe\sharkpay\Invoice\PayableAt::getMonth
     * @covers \domipoppe\sharkpay\Invoice\PayableAt::getYear
     */
    public function testGetDayYearAndMonth(): void
    {
        $dateTime = new \DateTime('2022-12-05');
        $payableAt = PayableAt::getPayableAtByDateTime($dateTime);
        $this->assertEquals('2022', $payableAt->getYear());
        $this->assertEquals('12', $payableAt->getMonth());
        $this->assertEquals('05', $payableAt->getDay());
    }
}
