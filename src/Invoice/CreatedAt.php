<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Invoice;

/**
 * Class CreatedAt
 *
 * @package domipoppe\sharkpay\Invoice
 */
class CreatedAt
{
    private \DateTime $createdAt;

    /**
     * @param \DateTime|null $createdAt
     */
    public function __construct(?\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt ?? new \DateTime();
    }

    /**
     * Will return a createdAt object with the given date time date
     *
     * @param \DateTime $dateTime
     *
     * @return self
     */
    public static function getCreatedAtByDateTime(\DateTime $dateTime): self
    {
        return new self($dateTime);
    }

    /**
     * Will return a createdAt with now date
     *
     * @return self
     */
    public static function getCreatedAtNow(): self
    {
        return new self();
    }

    /**
     * Will return a createdAt object which is in the future (now + days)
     *
     * @param int $days
     *
     * @return self
     */
    public static function getCreatedAtInDays(int $days): self
    {
        $date = new \DateTime();
        $date->modify(sprintf('+%d day', $days));
        return new self($date);
    }

    /**
     * Will return a createdAt object which is in the history (now - days)
     *
     * @param int $days
     *
     * @return self
     */
    public static function getCreatedAtBeforeDays(int $days): self
    {
        $date = new \DateTime();
        $date->modify(sprintf('-%d day', $days));
        return new self($date);
    }

    /**
     * Returns the created at date as a DateTime object
     *
     * @return \DateTime
     */
    public function getDateTime(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * Returns the created at date as a string in German format
     *
     * @return string
     */
    public function getGermanFormat(): string
    {
        return $this->getDateTime()->format('d.m.Y');
    }

    /**
     * Returns the created at date as a string in international format
     *
     * @return string
     */
    public function getInternationalFormat(): string
    {
        return $this->getDateTime()->format('Y-m-d');
    }

    /**
     * Returns the year of the created at date
     *
     * @return string
     */
    public function getYear(): string
    {
        return $this->getDateTime()->format('Y');
    }

    /**
     * Returns the month of the created at date
     *
     * @return string
     */
    public function getMonth(): string
    {
        return $this->getDateTime()->format('m');
    }

    /**
     * Returns the day of the created at date
     *
     * @return string
     */
    public function getDay(): string
    {
        return $this->getDateTime()->format('d');
    }
}