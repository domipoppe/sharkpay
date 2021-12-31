<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\GEO;

/**
 * Class Address
 *
 * @package domipoppe\sharkpay\Customer
 */
class Address
{
    private Country $country;
    /** @var string The first & last name (full-name) of the customer or the company name */
    private string $name;
    /** @var string The locality/city in which the street is, for example Mountain View */
    private string $locality;
    /** @var string The region where the locality is, for example California */
    private string $region;
    /** @var string The postal code */
    private string $postalCode;
    /** @var string The full street address, for example 1593 Californiastreet PK */
    private string $street;
    /** @var string|null E-Mail address for contact */
    private ?string $email;
    /** @var string|null Telephone Number for contact (should be provided in international format as: +49231241...) */
    private ?string $telephoneNumber;

    /**
     * @param Country $country
     * @param string $name
     * @param string $locality
     * @param string $region
     * @param string $postalCode
     * @param string $street
     * @param string|null $email
     * @param string|null $telephoneNumber
     */
    public function __construct(
        Country $country,
        string $name,
        string $locality,
        string $region,
        string $postalCode,
        string $street,
        ?string $email = null,
        ?string $telephoneNumber = null
    ) {
        $this->country = $country;
        $this->name = $name;
        $this->locality = $locality;
        $this->region = $region;
        $this->postalCode = $postalCode;
        $this->street = $street;
        $this->email = $email;
        $this->telephoneNumber = $telephoneNumber;
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
     * @return Country
     */
    public function getCountry(): Country
    {
        return $this->country;
    }

    /**
     * @param Country $country
     */
    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getLocality(): string
    {
        return $this->locality;
    }

    /**
     * @param string $locality
     */
    public function setLocality(string $locality): void
    {
        $this->locality = $locality;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @param string $region
     */
    public function setRegion(string $region): void
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getTelephoneNumber(): ?string
    {
        return $this->telephoneNumber;
    }

    /**
     * @param string|null $telephoneNumber
     */
    public function setTelephoneNumber(?string $telephoneNumber): void
    {
        $this->telephoneNumber = $telephoneNumber;
    }
}