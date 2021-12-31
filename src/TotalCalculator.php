<?php

declare(strict_types=1);

namespace domipoppe\sharkpay;

use domipoppe\sharkpay\Exception\MixedCurrenciesException;
use domipoppe\sharkpay\Exception\UnknownDiscountTypeException;

/**
 * Class TotalCalculator
 *
 * This class will calculate total costs of an Order (substracting discounts, splitting tax etc)
 *
 * @package domipoppe\sharkpay
 */
class TotalCalculator
{
    /**
     * @param Order $order
     * @return Total
     *
     * @throws MixedCurrenciesException
     * @throws UnknownDiscountTypeException
     */
    public static function getTotal(Order $order): Total
    {
        $currency = $order->getPositions()[0]->getPrice()->getCurrency();

        $totalPrices = self::calculatePositionPrices($order, $currency);
        $totalNetto = $totalPrices['totalNetto'];
        $totalTax = $totalPrices['totalTax'];
        $totalBrutto = $totalPrices['totalBrutto'];
        $taxPositions = $totalPrices['taxPositions'];

        $discountPosition = self::calculateDiscounts($order, $totalNetto, $currency);
        if ($discountPosition instanceof Position) {
            $order->addPosition($discountPosition);
            $totalPrices = self::calculatePositionPrices($order, $currency);
            $totalNetto = $totalPrices['totalNetto'];
            $totalTax = $totalPrices['totalTax'];
            $totalBrutto = $totalPrices['totalBrutto'];
            $taxPositions = $totalPrices['taxPositions'];
        }

        // this is the totalPrices, the tax is set to 0% as they should not calculate/add anything to the price
        // so whatever you might get from it (getBrutto() - getNetto()) it's always the same
        // we mock it like this to ensure our format is correct, and we don't cause any calculation issues
        $totalPriceNetto = new Price($totalNetto, $currency, new Tax('NONE', 0));
        $totalPriceTax = new Price($totalTax, $currency, new Tax('NONE', 0));
        $totalPriceBrutto = new Price($totalBrutto, $currency, new Tax('NONE', 0));

        return new Total($totalPriceNetto, $totalPriceTax, $totalPriceBrutto, $order->getDiscounts(), $taxPositions);
    }

    /**
     * @param Order $order
     * @param float $totalNetto
     * @param Currency\Currency $currency
     * @return Position|null
     * @throws UnknownDiscountTypeException
     */
    private static function calculateDiscounts(Order $order, float $totalNetto, Currency\Currency $currency): ?Position
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
     * @param Order $order
     * @param Currency\Currency $currency
     * @return array
     * @throws MixedCurrenciesException
     */
    private static function calculatePositionPrices(Order $order, Currency\Currency $currency): array
    {
        $taxPositions = [];
        $totalNetto = 0.00;
        $totalTax = 0.00;
        $totalBrutto = 0.00;

        foreach ($order->getPositions() as $curPosition) {
            if ($currency->getIsoCode() !== $curPosition->getPrice()->getCurrency()->getIsoCode()) {
                throw new MixedCurrenciesException('The order has mixed currencies - never mix currencies in one order!');
            }

            $curTaxKey = $curPosition->getPrice()->getTax()->getKey();
            if (empty($taxPositions[$curTaxKey])) {
                $taxPositions[$curTaxKey] = ['amount' => $curPosition->getTotalTax(), 'rate' => $curPosition->getTaxRate()];
            } else {
                $taxPositions[$curTaxKey]['amount'] += $curPosition->getTotalTax();
            }

            $totalNetto += $curPosition->getTotalNetto();
            $totalTax += $curPosition->getTotalTax();
            $totalBrutto += $curPosition->getTotalBrutto();
        }

        return [
            'taxPositions' => $taxPositions,
            'totalNetto' => $totalNetto,
            'totalTax' => $totalTax,
            'totalBrutto' => $totalBrutto
        ];
    }
}