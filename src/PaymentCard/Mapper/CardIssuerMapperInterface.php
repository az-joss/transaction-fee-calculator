<?php

namespace Tfc\PaymentCard\Mapper;

use Tfc\DataModel\CardIssuer;

interface CardIssuerMapperInterface
{
    public function mapArrayToCardIssuer(array $data): CardIssuer;
}