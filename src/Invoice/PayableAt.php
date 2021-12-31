<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Invoice;

/**
 * Class PayableAt
 *
 * @package domipoppe\sharkpay\Invoice
 */
class PayableAt
{
    private \DateTime $payableAt;

    /**
     * @param \DateTime|null $payableAt
     */
    public function __construct(\DateTime $payableAt = null)
    {
        $this->payableAt = $payableAt ?? new \DateTime();
    }

    /**
     * Will return a payable at object with the given date time date
     *
     * @param \DateTime $dateTime
     *
     * @return self
     */
    public static function getPayableAtByDateTime(\DateTime $dateTime): self
    {
        return new self($dateTime);
    }

    /**
     * Will return a payableAt with now date
     *
     * @return self
     */
    public static function getPayableAtNow(): self
    {
        return new self();
    }

    /**
     * Will return a payableAt object which needs to be paid in the future (now + days)
     *
     * @param int $days
     *
     * @return self
     */
    public static function getPayableAtInDays(int $days): self
    {
        $date = new \DateTime();
        $date->modify(sprintf('+%d day', $days));
        return new self($date);
    }

    /**
     * Returns the payable at date as a DateTime object
     *
     * @return \DateTime
     */
    public function getDateTime(): \DateTime
    {
        return $this->payableAt;
    }

    /**
     * Returns the payable at date as a string in German format
     *
     * @return string
     */
    public function getGermanFormat(): string
    {
        return $this->getDateTime()->format('d.m.Y');
    }

    /**
     * Returns the payable at date as a string in international format
     *
     * @return string
     */
    public function getInternationalFormat(): string
    {
        return $this->getDateTime()->format('Y-m-d');
    }

    /**
     * Returns the year of the payable at date
     *
     * @return string
     */
    public function getYear(): string
    {
        return $this->getDateTime()->format('Y');
    }

    /**
     * Returns the month of the payable at date
     *
     * @return string
     */
    public function getMonth(): string
    {
        return $this->getDateTime()->format('m');
    }

    /**
     * Returns the day of the payable at date
     *
     * @return string
     */
    public function getDay(): string
    {
        return $this->getDateTime()->format('d');
    }
}