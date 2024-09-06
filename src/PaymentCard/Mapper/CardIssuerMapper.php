<?php

namespace Tfc\PaymentCard\Mapper;

use Money\Currency;
use Tfc\DataModel\CardIssuer;
use Tfc\DataModel\Country;

class CardIssuerMapper implements CardIssuerMapperInterface
{
    public function mapArrayToCardIssuer(array $data): CardIssuer
    {
        $data['country'] = $this->mapArrayToCountry($data['country'] ?? []);

        return CardIssuer::fromArray($data);
    }

    protected function mapArrayToCountry(array $data): Country
    {
        $data['currency'] = new Currency($data['currency']);

        return Country::fromArray($data);
    }
}