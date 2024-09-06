<?php

namespace Tfc\PaymentCard;

use Tfc\DataModel\CardIssuer;

interface PaymentCardFacadeInterface
{
    public function getPaymentCardIssuer(string $binCode): CardIssuer;
}