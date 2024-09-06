<?php

namespace Tfc\PaymentCard;

use Tfc\DataModel\CardIssuer;

class PaymentCardFacade implements PaymentCardFacadeInterface
{
    public function __construct(
        protected PaymentCardFactory $factory
    ) {}

    public function getPaymentCardIssuer(string $binCode): CardIssuer
    {
        return $this->factory->createDummyCardIssuerProvider()
            ->getCartIssuer($binCode);
    }
}