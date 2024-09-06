<?php

namespace Tfc\ExchangeRate;

use Money\Currencies\CurrencyList;
use Money\Currency;
use Money\CurrencyPair;

class ExchangeRateFacade implements ExchangeRateFacadeInterface
{
    public function __construct(
        protected ExchangeRateFactory $factory
    ) {}

    /**
     * @param \Money\Currency $baseCurrency
     * @param \Money\CurrencyList|null $counterCurrencies
     * 
     * @return array<\Money\CurrencyPair>
     */
    public function getExchangeRates(Currency $baseCurrency, ?CurrencyList $counterCurrencies = null): array
    {
        return $this->factory->createDummyProvider()
            ->getExchangeRates($baseCurrency, $counterCurrencies);
    }

    public function getExchangePair(Currency $baseCurrency, Currency $counterCurrency): CurrencyPair
    {
        return $this->factory->createDummyProvider()
            ->getExchangePair($baseCurrency, $counterCurrency);
    }
}