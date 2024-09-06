<?php

namespace Tfc\ExchangeRatesApi;

interface ExchangeRatesApiClientInterface
{
    public function getRates(
        ?string $baseCurrency,
        array $counterCurrencies = []
    ): array;
}