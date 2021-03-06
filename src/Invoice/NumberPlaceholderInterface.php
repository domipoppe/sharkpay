<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Invoice;

/**
 * Interface NumberPlaceholderInterface
 *
 * @package domipoppe\sharkpay\Invoice
 */
interface NumberPlaceholderInterface
{
    /*
     * BILLING_ADDRESS Placeholder
     *
     * GEO\Address::class
     */
    public const PLACEHOLDER_BILLING_ADDRESS_COUNTRY_ALPHA2 = 'BILLING_ADDRESS_COUNTRY_ALPHA2';
    public const PLACEHOLDER_BILLING_ADDRESS_COUNTRY_NAME = 'BILLING_ADDRESS_COUNTRY_NAME';
    public const PLACEHOLDER_BILLING_ADDRESS_NAME = 'BILLING_ADDRESS_NAME';
    public const PLACEHOLDER_BILLING_ADDRESS_LOCALITY = 'BILLING_ADDRESS_LOCALITY';
    public const PLACEHOLDER_BILLING_ADDRESS_REGION = 'BILLING_ADDRESS_REGION';
    public const PLACEHOLDER_BILLING_ADDRESS_POSTAL_CODE = 'BILLING_ADDRESS_POSTAL_CODE';
    public const PLACEHOLDER_BILLING_ADDRESS_STREET = 'BILLING_ADDRESS_STREET';
    public const PLACEHOLDER_BILLING_ADDRESS_EMAIL = 'BILLING_ADDRESS_EMAIL';
    public const PLACEHOLDER_BILLING_ADDRESS_TELEPHONE_NUMBER = 'BILLING_ADDRESS_TELEPHONE_NUMBER';

    /*
     * DELIVERY_ADDRESS Placeholder
     *
     * GEO\Address::class
     */
    public const PLACEHOLDER_DELIVERY_ADDRESS_COUNTRY_ALPHA2 = 'DELIVERY_ADDRESS_COUNTRY_ALPHA2';
    public const PLACEHOLDER_DELIVERY_ADDRESS_COUNTRY_NAME = 'DELIVERY_ADDRESS_COUNTRY_NAME';
    public const PLACEHOLDER_DELIVERY_ADDRESS_NAME = 'DELIVERY_ADDRESS_NAME';
    public const PLACEHOLDER_DELIVERY_ADDRESS_LOCALITY = 'DELIVERY_ADDRESS_LOCALITY';
    public const PLACEHOLDER_DELIVERY_ADDRESS_REGION = 'DELIVERY_ADDRESS_REGION';
    public const PLACEHOLDER_DELIVERY_ADDRESS_POSTAL_CODE = 'DELIVERY_ADDRESS_POSTAL_CODE';
    public const PLACEHOLDER_DELIVERY_ADDRESS_STREET = 'DELIVERY_ADDRESS_STREET';
    public const PLACEHOLDER_DELIVERY_ADDRESS_EMAIL = 'DELIVERY_ADDRESS_EMAIL';
    public const PLACEHOLDER_DELIVERY_ADDRESS_TELEPHONE_NUMBER = 'DELIVERY_ADDRESS_TELEPHONE_NUMBER';
}