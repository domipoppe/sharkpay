<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\GEO;

/**
 * Class Country
 *
 * https://en.wikipedia.org/wiki/List_of_ISO_3166_country_codes
 *
 * @package domipoppe\sharkpay\Customer
 */
class Country
{
    private string $alpha2;
    private string $name;
    private string $code;

    /**
     * @param string $alpha2
     * @param string $name
     * @param string $code
     */
    public function __construct(string $alpha2, string $name, string $code)
    {
        $this->alpha2 = $alpha2;
        $this->name = $name;
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getAlpha2(): string
    {
        return $this->alpha2;
    }

    /**
     * @param string $alpha2
     */
    public function setAlpha2(string $alpha2): void
    {
        $this->alpha2 = $alpha2;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }
}