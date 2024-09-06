<?php

namespace Tfc\PaymentCard;

use Tfc\BinListApi\BinListApiClient;
use Tfc\BinListApi\BinListApiClientInterface;
use Tfc\PaymentCard\Mapper\CardIssuerMapper;
use Tfc\PaymentCard\Mapper\CardIssuerMapperInterface;
use Tfc\PaymentCard\Provider\BinListApiCardIssuerProvider;
use Tfc\PaymentCard\Provider\CardIssuerProviderInterface;
use Tfc\PaymentCard\Provider\DummyCardIssuerProvider;

class PaymentCardFactory
{
    public function createDummyCardIssuerProvider(): CardIssuerProviderInterface
    {
        return new DummyCardIssuerProvider(
            $this->createCardIssuerMapper(),
        );
    }

    public function createBinListCardIssuerProvider(): CardIssuerProviderInterface
    {
        return new BinListApiCardIssuerProvider(
            $this->provideBinListApiClient(),
            $this->createCardIssuerMapper(),
        );
    }

    public function createCardIssuerMapper(): CardIssuerMapperInterface
    {
        return new CardIssuerMapper();
    }

    public function provideBinListApiClient(): BinListApiClientInterface
    {
        return new BinListApiClient();
    }
}