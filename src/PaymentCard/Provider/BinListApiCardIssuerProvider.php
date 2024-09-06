<?php

namespace Tfc\PaymentCard\Provider;

use RuntimeException;
use Tfc\BinListApi\BinListApiClientInterface;
use Tfc\DataModel\CardIssuer;
use Tfc\PaymentCard\Mapper\CardIssuerMapperInterface;

class BinListApiCardIssuerProvider implements CardIssuerProviderInterface
{
    public function __construct(
        protected BinListApiClientInterface $binListClient,
        protected CardIssuerMapperInterface $cardIssuerMapper
    ) {}

    public function getCartIssuer(string $binCode): CardIssuer
    {
        $data = $this->binListClient->getCardIssuer($binCode);

        return $this->cardIssuerMapper->mapArrayToCardIssuer($data);
    }
}
