<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Currency;

/**
 * Class USD
 *
 * @package domipoppe\sharkpay\Currencies
 */
class USD extends Currency
{
    /** @inheritDoc */
    public function __construct()
    {
        parent::__construct(',', '.', 'USD', 'Dollar', '$', 'Cent', 'ct', 100);
    }
}