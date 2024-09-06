<?php

namespace Tfc\ExchangeRate\Provider;

use Money\Currency;
use Money\CurrencyPair;

abstract class AbstractExchangeRateProvider
{
    protected function createCurrencyPair(
        Currency $baseCurrency,
        Currency $counterCurrency,
        float $rateValue,
    ): CurrencyPair {
        return new CurrencyPair(
            $baseCurrency,
            $counterCurrency,
            $rateValue,
        );
    }

    protected function generatePairIndex(string $baseCurrencyCode, string $counterCurrencyCode): string
    {
        return sprintf('%s:%s', $baseCurrencyCode, $counterCurrencyCode);
    }
}