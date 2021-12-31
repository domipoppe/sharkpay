<?php

declare(strict_types=1);

namespace domipoppe\sharkpay\Total;

use domipoppe\sharkpay\Currency\Currency;
use domipoppe\sharkpay\Exception\MixedCurrenciesException;
use domipoppe\sharkpay\Exception\TaxKeyMixedRatesException;
use domipoppe\sharkpay\Exception\UnknownDiscountTypeException;
use domipoppe\sharkpay\Order;
use domipoppe\sharkpay\Position;
use domipoppe\sharkpay\Price;
use domipoppe\sharkpay\Tax\Tax;
use domipoppe\sharkpay\Tax\TotalTaxHandler;

/**
 * Class TotalCalculator
 *
 * This class will calculate total costs of an Order (removing discounts, splitting tax etc.)
 *
 * @package domipoppe\sharkpay
 */
class TotalCalculator
{
    /**
     * @param Order $order
     *
     * @return Total
     *
     * @throws MixedCurrenciesException
     * @throws UnknownDiscountTypeException
     * @throws TaxKeyMixedRatesException
     */
    public static function getTotal(Order $order): Total
    {
        $currency = $order->getPositions()[0]->getSinglePrice()->getCurrency();

        $totalPriceSummary = self::calculatePositionPrices($order, $currency);
        $totalNetto = $totalPriceSummary->getTotalNetto();
        $totalTax = $totalPriceSummary->getTotalTax();
        $totalBrutto = $totalPriceSummary->getTotalBrutto();
        $taxPositions = $totalPriceSummary->getTaxPositions();

        $discountPosition = self::calculateDiscounts($order, $totalNetto, $currency);
        if ($discountPosition instanceof Position) {
            $order->addPosition($discountPosition);
            $totalPriceSummary = self::calculatePositionPrices($order, $currency);
            $totalNetto = $totalPriceSummary->getTotalNetto();
            $totalTax = $totalPriceSummary->getTotalTax();
            $totalBrutto = $totalPriceSummary->getTotalBrutto();
            $taxPositions = $totalPriceSummary->getTaxPositions();
        }

        // this is the totalPrices (summarized of all other totals), tax is set to 0% as we do not want any calculation here anymore
        $totalPriceNetto = new Price($totalNetto, $currency, new Tax('NONE', 0));
        $totalPriceTax = new Price($totalTax, $currency, new Tax('NONE', 0));
        $totalPriceBrutto = new Price($totalBrutto, $currency, new Tax('NONE', 0));

        return new Total($totalPriceNetto, $totalPriceTax, $totalPriceBrutto, $order->getDiscounts(), $taxPositions);
    }

    /**
     * @param Order    $order
     * @param float    $totalNetto
     * @param Currency $currency
     *
     * @return Position|null
     * @throws UnknownDiscountTypeException
     */
    private static function calculateDiscounts(Order $order, float $totalNetto, Currency $currency): ?Position
    {
        if (\count($order->getDiscounts()) > 0) {
            $totalDiscountAmount = 0.00;

            foreach ($order->getDiscounts() as $curDiscountPosition) {
                $curDiscount = $curDiscountPosition->getDiscount($totalNetto);
                $totalDiscountAmount += $curDiscountPosition->getDiscount($totalNetto);
                $totalNetto -= $curDiscount;
            }

            $totalDiscountAmount = -$totalDiscountAmount;

            $averageTax = 0.00;
            foreach ($order->getPositions() as $curPosition) {
                $averageTax += $curPosition->getTaxRate();
            }
            $averageTax /= \count($order->getPositions());

            $discountPrice = new Price($totalDiscountAmount, $currency, new Tax('NONE', $averageTax));
            $discountPosition = new Position($discountPrice, 1);
            $discountPosition->setIdentifier('discount');
            $discountPosition->setName('Discount');
            $discountPosition->setDescription('Discount');

            return $discountPosition;
        }

        return null;
    }

    /**
     * @param Order    $order
     * @param Currency $currency
     *
     * @return TotalSummary
     * @throws MixedCurrenciesException
     * @throws TaxKeyMixedRatesException
     */
    private static function calculatePositionPrices(Order $order, Currency $currency): TotalSummary
    {
        $totalTaxHandler = new TotalTaxHandler();
        $totalNetto = 0.00;
        $totalTax = 0.00;
        $totalBrutto = 0.00;

        foreach ($order->getPositions() as $curPosition) {
            if ($currency->getIsoCode() !== $curPosition->getSinglePrice()->getCurrency()->getIsoCode()) {
                throw new MixedCurrenciesException(
                    'The order has mixed currencies - never mix currencies in one order!'
                );
            }

            $totalTaxHandler->addTax(
                $curPosition->getSinglePrice()->getTax()->getKey(),
                $curPosition->getTotalTax(),
                $curPosition->getTaxRate()
            );
            $totalNetto += $curPosition->getTotalNetto();
            $totalTax += $curPosition->getTotalTax();
            $totalBrutto += $curPosition->getTotalBrutto();
        }

        return new TotalSummary($totalTaxHandler->getTaxPositions(), $totalNetto, $totalTax, $totalBrutto);
    }
}