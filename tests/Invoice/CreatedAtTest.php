<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Tests\Invoice;

use domipoppe\sharkpay\Invoice\CreatedAt;
use PHPUnit\Framework\TestCase;

/**
 * Class CreatedAtTest
 *
 * @package domipoppe\sharkpay\Tests\Invoice
 */
class CreatedAtTest extends TestCase
{
    /**
     * @covers \domipoppe\sharkpay\Invoice\CreatedAt::getCreatedAtByDateTime
     */
    public function testGetCreatedAtByDateTime(): void
    {
        $dateTime = new \DateTime();
        $payableAt = CreatedAt::getCreatedAtByDateTime($dateTime);
        $this->assertEquals($dateTime, $payableAt->getDateTime());
    }

    /**
     * @covers \domipoppe\sharkpay\Invoice\CreatedAt::getCreatedAtInDays
     */
    public function testGetCreatedAtInDays(): void
    {
        $dateTime = new \DateTime();
        $dateTime->modify('+20 day');
        $createdAt = CreatedAt::getCreatedAtInDays(20);
        $this->assertEquals($dateTime->format('Y-m-d'), $createdAt->getDateTime()->format('Y-m-d'));
    }

    /**
     * @covers \domipoppe\sharkpay\Invoice\CreatedAt::getCreatedAtNow
     */
    public function testGetCreatedAtNow(): void
    {
        $dateTime = new \DateTime();
        $createdAt = CreatedAt::getCreatedAtNow();
        $this->assertEquals($dateTime->format('Y-m-d'), $createdAt->getInternationalFormat());
    }

    /**
     * @covers \domipoppe\sharkpay\Invoice\CreatedAt::getCreatedAtBeforeDays
     */
    public function testGetCreatedBeforeInDays(): void
    {
        $dateTime = new \DateTime();
        $dateTime->modify('-20 day');
        $createdAt = CreatedAt::getCreatedAtBeforeDays(20);
        $this->assertEquals($dateTime->format('Y-m-d'), $createdAt->getDateTime()->format('Y-m-d'));
    }

    /**
     * @covers \domipoppe\sharkpay\Invoice\CreatedAt::getGermanFormat
     */
    public function testGetCreatedAtGermanFormat(): void
    {
        $dateTime = new \DateTime('2022-12-20');
        $createdAt = CreatedAt::getCreatedAtByDateTime($dateTime);
        $this->assertEquals('20.12.2022', $createdAt->getGermanFormat());
    }

    /**
     * @covers \domipoppe\sharkpay\Invoice\CreatedAt::getInternationalFormat
     */
    public function testGetCreatedAtInternationalFormat(): void
    {
        $dateTime = new \DateTime('2022-12-20');
        $createdAt = CreatedAt::getCreatedAtByDateTime($dateTime);
        $this->assertEquals('2022-12-20', $createdAt->getInternationalFormat());
    }

    /**
     * @covers \domipoppe\sharkpay\Invoice\CreatedAt::getDay
     * @covers \domipoppe\sharkpay\Invoice\CreatedAt::getMonth
     * @covers \domipoppe\sharkpay\Invoice\CreatedAt::getYear
     */
    public function testGetCreatedAtDayYearAndMonth(): void
    {
        $dateTime = new \DateTime('2022-12-05');
        $createdAt = CreatedAt::getCreatedAtByDateTime($dateTime);
        $this->assertEquals('2022', $createdAt->getYear());
        $this->assertEquals('12', $createdAt->getMonth());
        $this->assertEquals('05', $createdAt->getDay());
    }
}
