<?php

namespace Tfc\ExchangeRate\Provider;

use Money\Currencies\CurrencyList;
use Money\Currency;
use Money\CurrencyPair;

interface ExchangeRateProviderInterface
{
    /**
     * @param \Money\Currency $baseCurrency
     * @param \Money\CurrencyList|null $counterCurrencies
     * @param bool $isReversPair
     * 
     * @return array<\Money\CurrencyPair>
     */
    public function getExchangeRates(
        Currency $baseCurrency,
        ?CurrencyList $counterCurrencies = null,
        bool $isReversPair = false,
    ): array;

    public function getExchangePair(Currency $baseCurrency, Currency $counterCurrency): CurrencyPair;
}