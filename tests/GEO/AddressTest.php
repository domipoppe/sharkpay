<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Tests\GEO;

use domipoppe\sharkpay\GEO\Address;
use domipoppe\sharkpay\GEO\Country;
use PHPUnit\Framework\TestCase;

/**
 * Class AddressTest
 *
 * @package domipoppe\sharkpay\Tests\Customer
 */
class AddressTest extends TestCase
{
    /**
     * @covers \domipoppe\sharkpay\GEO\Address
     * @covers \domipoppe\sharkpay\GEO\Country
     * @throws \JsonException
     */
    public function testAddress(): void
    {
        $country = new Country('DE', 'Germany', '276');
        $address = new Address($country, 'Max Muster', 'Musterstadt', 'Bayern', '94221', 'Gartenstraße 5');

        $this->assertEquals('276', $address->getCountry()->getCode());
        $this->assertEquals('Musterstadt', $address->getLocality());
    }
}
