<?php

namespace Tfc\PaymentCard\Provider;

use RuntimeException;
use Tfc\DataModel\CardIssuer;
use Tfc\PaymentCard\Mapper\CardIssuerMapperInterface;

class DummyCardIssuerProvider implements CardIssuerProviderInterface
{
    protected array $binCodeIssurers = [
        '45717360' => [
            'scheme' => 'visa',
            'type' => 'debit',
            'brand' => 'Visa/Dankort',
            'prepaid' => false,
            'country' => [
                'numeric' => '208',
                'alpha2' => 'DK',
                'name' => 'Denmark',
                'currency' => 'DKK',
            ],
        ],
        '516793' => [
            'scheme' => 'visa',
            'type' => 'debit',
            'brand' => 'Visa/Dankort',
            'prepaid' => false,
            'country' => [
                'numeric' => '208',
                'alpha2' => 'DK',
                'name' => 'Denmark',
                'currency' => 'DKK',
            ],
        ],
        '45417360' => [
            'scheme' => 'visa',
            'type' => 'credit',
            'brand' => 'Visa Classic',
            'prepaid' => false,
            'country' => [
                'numeric' => '392',
                'alpha2' => 'JP',
                'name' => 'Japan',
                'emoji' => '🇯🇵',
                'currency' => 'JPY',
            ],
        ],
        '41417360' => [
            'scheme' => 'visa',
            'type' => 'credit',
            'brand' => 'Visa Classic',
            'prepaid' => false,
            'country' => [
                'numeric' => '392',
                'alpha2' => 'US',
                'name' => 'United States of America',
                'currency' => 'USD',
            ],
        ],
        '4745030' => [
            'scheme' => 'visa',
            'type' => 'credit',
            'brand' => 'Visa Classic',
            'prepaid' => false,
            'country' => [
                'numeric' => '392',
                'alpha2' => 'UK',
                'name' => 'United Kingdom',
                'currency' => 'GBP',
            ],
        ],
        '54562793' => [
            'scheme' => 'mastercrd',
            'type' => 'credit',
            'brand' => 'Platinum Mastercard',
            'prepaid' => false,
            'country' => [
                'numeric' => '352',
                'alpha2' => 'IS',
                'name' => 'Iceland',
                'currency' => 'ISK',
            ],
        ],
    ];

    public function __construct(
        protected CardIssuerMapperInterface $cardIssuerMapper
    ) {}

    public function getCartIssuer(string $binCode): CardIssuer
    {
        if (!isset($this->binCodeIssurers[$binCode])) {
            throw new RuntimeException("Nothing found by bin code: $binCode.");
        }

        return $this->cardIssuerMapper->mapArrayToCardIssuer($this->binCodeIssurers[$binCode]);
    }
}
