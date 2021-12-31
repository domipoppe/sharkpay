<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Currency;

/**
 * Class EUR
 *
 * @package domipoppe\sharkpay\Currencies
 */
class EUR extends Currency
{
    /** @inheritDoc */
    public function __construct()
    {
        parent::__construct('.', ',', 'EUR', 'Euro', '€', 'Cent', 'ct', 100);
    }
}