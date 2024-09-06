<?php

namespace Tfc\ExchangeRate\Provider;

use ArrayIterator;
use Iterator;
use Money\Currencies\CurrencyList;
use Money\Currency;
use Money\CurrencyPair;
use RuntimeException;

class DummyExchangeRateProvider extends AbstractExchangeRateProvider implements ExchangeRateProviderInterface
{
    protected array $rates = [
        'EUR' => [
            'EUR' => 1,
            'GBP' => 0.8413,
            'USD' => 1.1036,
            'JPY' => 161.1587,
            'CHF' => 0.9402,
            'ISK' => 153.30,
        ],
    ];

    public function getExchangeRates(
        Currency $baseCurrency,
        ?CurrencyList $counterCurrencies = null,
        bool $isReversPair = false,
    ): array {
        if (!isset($this->rates[$baseCurrency->getCode()])) {
            return [];
        }

        if (!$counterCurrencies || !iterator_count($counterCurrencies->getIterator())) {
            $counterCurrencyIterator = $this->getAllCounterCurrencyIterator($baseCurrency);
        } else {
            $counterCurrencyIterator = $counterCurrencies->getIterator();
        }

        $currencyPairs = [];

        foreach ($counterCurrencyIterator as $counterCurrency) {
            $rateValue = $this->rates[$baseCurrency->getCode()][$counterCurrency->getCode()];
            $currencyPairs[] = $this->createCurrencyPair(
                $baseCurrency,
                $counterCurrency,
                $rateValue,
            );
        }

        return $currencyPairs;
    }

    public function getExchangePair(Currency $baseCurrency, Currency $counterCurrency): CurrencyPair
    {
        $baseCurrencyCode = $baseCurrency->getCode();
        $counterCurrencyCode = $counterCurrency->getCode();

        $rateValue = null;

        if (isset($this->rates[$baseCurrencyCode][$counterCurrencyCode])) {
            $rateValue = $this->rates[$baseCurrencyCode][$counterCurrencyCode];
        } elseif (isset($this->rates[$counterCurrencyCode][$baseCurrencyCode])) {
            $rateValue = 1 / $this->rates[$counterCurrencyCode][$baseCurrencyCode];
        }

        if (!$rateValue) {
            throw new RuntimeException("Echange rate not found for $baseCurrencyCode:$counterCurrencyCode");
        }

        return $this->createCurrencyPair(
            $baseCurrency,
            $counterCurrency,
            $rateValue,
        );
    }

    protected function getAllCounterCurrencyIterator(Currency $baseCurrency): Iterator
    {
        return new ArrayIterator(array_map(
            function(string $currencyCode): Currency {
                return new Currency($currencyCode);
            },
            array_keys($this->rates[$baseCurrency->getCode()])
        ));
    }
}
