<?php

namespace Tfc\PaymentCard\Provider;

use Tfc\DataModel\CardIssuer;

interface CardIssuerProviderInterface
{
    public function getCartIssuer(string $binCode): CardIssuer;
}