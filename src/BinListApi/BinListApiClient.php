<?php

namespace Tfc\BinListApi;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use RuntimeException;

class BinListApiClient implements BinListApiClientInterface
{
    protected ClientInterface $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => getenv('BINLIST_HOST'),
        ]);
    }

    public function getCardIssuer(string $binCode): array
    {
        return $this->executeRequest('GET', $binCode, []);
    }

    protected function executeRequest(string $method, string $path, array $options = []): array
    {
        $response = $this->httpClient->request($method, $path, $options);

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException('Response error: ' . $response->getStatusCode());
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}