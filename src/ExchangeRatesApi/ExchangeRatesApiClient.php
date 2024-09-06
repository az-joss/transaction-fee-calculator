<?php

namespace Tfc\ExchangeRatesApi;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use RuntimeException;

class ExchangeRatesApiClient implements ExchangeRatesApiClientInterface
{
    protected ClientInterface $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => getenv('ECHANGE_RATES_API_HOST'),
        ]);
    }

    public function getRates(
        ?string $baseCurrency,
        array $counterCurrencies = []
    ): array
    {
        $options = [];
        $options = $this->mapBaseCurrency($options, $baseCurrency);
        $options = $this->mapCounterCurrencies($options, $counterCurrencies);

        return $this->executeRequest(
            'GET',
            getenv('ECHANGE_RATES_API_ENDPOINT_RATE'),
            $options
        );
    }

    protected function mapBaseCurrency(array $options, ?string $baseCurrency): array
    {
        if (is_string($baseCurrency) && strlen($baseCurrency) > 0) {
            $options['base'] = $baseCurrency;
        }

        return $options;
    }

    protected function mapCounterCurrencies(array $options, array $counterCurrencies): array
    {
        if (count($counterCurrencies) > 0) {
            $options['symbols'] = implode(',', $counterCurrencies);
        }

        return $options;
    }

    protected function executeRequest(string $method, string $path, array $options = []): array
    {
        $options['query']['access_key'] = getenv('ECHANGE_RATES_API_ACCESS_KEY');

        $response = $this->httpClient->request($method, $path, $options);

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException('Response error: ' . $response->getStatusCode());
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}