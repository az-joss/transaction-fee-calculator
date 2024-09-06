<?php

namespace Tfc\ExchangeRate;

use Money\Currencies\CurrencyList;
use Money\Currency;
use Money\CurrencyPair;

interface ExchangeRateFacadeInterface
{
    /**
     * @param \Money\Currency $baseCurrency
     * @param \Money\CurrencyList|null $counterCurrencies
     * 
     * @return array<\Money\CurrencyPair>
     */
    public function getExchangeRates(Currency $baseCurrency, ?CurrencyList $counterCurrencies = null): array;

    public function getExchangePair(Currency $baseCurrency, Currency $counterCurrency): CurrencyPair;
}