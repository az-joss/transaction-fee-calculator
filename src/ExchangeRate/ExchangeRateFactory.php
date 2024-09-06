<?php

namespace Tfc\ExchangeRate;

use Tfc\ExchangeRate\Provider\DummyExchangeRateProvider;
use Tfc\ExchangeRate\Provider\ExchangeRateProviderInterface;
use Tfc\ExchangeRate\Provider\ExchangeRatesApiExchangeRateProvider;
use Tfc\ExchangeRatesApi\ExchangeRatesApiClient;
use Tfc\ExchangeRatesApi\ExchangeRatesApiClientInterface;

class ExchangeRateFactory
{
    public function createDummyProvider(): ExchangeRateProviderInterface
    {
        return new DummyExchangeRateProvider();
    }

    public function createExchangeRatesApiProvider(): ExchangeRateProviderInterface
    {
        return new ExchangeRatesApiExchangeRateProvider(
            $this->provideExchangeRatesApiClient(),
        );
    }

    public function provideExchangeRatesApiClient(): ExchangeRatesApiClientInterface
    {
        return new ExchangeRatesApiClient();
    }
}