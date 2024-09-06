<?php

namespace Tfc\ExchangeRate\Provider;


use Money\Currencies\CurrencyList;
use Money\Currency;
use Money\CurrencyPair;
use RuntimeException;
use Tfc\ExchangeRatesApi\ExchangeRatesApiClientInterface;

class ExchangeRatesApiExchangeRateProvider extends AbstractExchangeRateProvider implements ExchangeRateProviderInterface
{
    /**
     * @var array<string, \Money\CurrencyPair>
     */
    protected array $pairsPool = [];

    public function __construct(
        protected ExchangeRatesApiClientInterface $client,
    ) {}

    public function getExchangeRates(
        Currency $baseCurrency,
        ?CurrencyList $counterCurrencies = null,
        bool $isReversPair = false,
    ): array {
        $baseCurrencyCode = $baseCurrency->getCode();
        $counterCurrencyCodes = [];

        if ($counterCurrencies !== null) {
            foreach($counterCurrencies->getIterator() as $counterCurrency) {
                $counterCurrencyCodes[] = $counterCurrency->getCode();
            }   
        }

        $data = $this->client->getRates(
            $baseCurrencyCode,
            $counterCurrencyCodes,
        );

        $currencyPairs = $this->mapArayToCurrencyPairs($data);
        $this->pairsPool = array_merge($this->pairsPool, $currencyPairs);

        return $currencyPairs;
    }

    public function getExchangePair(Currency $baseCurrency, Currency $counterCurrency): CurrencyPair
    {
        $pairIndex = $this->generatePairIndex($baseCurrency->getCode(), $counterCurrency->getCode());
        
        if (isset($this->pairsPool[$pairIndex])) {
            return $this->pairsPool[$pairIndex];    
        }

        $currencyPairs = $this->getExchangeRates($baseCurrency);

        if (isset($currencyPairs[$pairIndex])) {
            return $currencyPairs[$pairIndex];
        }

        throw new RuntimeException(sprintf(
            'Echange pair note found for %s.',
            $pairIndex,
        ));
    }

    protected function mapArayToCurrencyPairs(array $data): array
    {
        $baseCurrencyCode = $data['base'];
        $baseCurrency = new Currency($baseCurrencyCode);
        $pairs = [];

        foreach($data['rates'] as $counterCurrencyCode => $rateValue) {
            $index = $this->generatePairIndex($baseCurrencyCode, $counterCurrencyCode);
            $reverseIndex = $this->generatePairIndex($counterCurrencyCode, $baseCurrencyCode);
            $counterCurrency = new Currency($counterCurrencyCode);

            $pairs[$index] = $this->createCurrencyPair(
                $baseCurrency,
                $counterCurrency,
                (float)$rateValue,
            );
            $pairs[$reverseIndex] = $this->createCurrencyPair(
                $counterCurrency,
                $baseCurrency,
                1 / (float)$rateValue,
            );
        }

        return $pairs;
    }
}
