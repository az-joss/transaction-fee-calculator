<?php

namespace Tfc\FeeCalculator;

use Money\Converter;
use Money\Currencies\ISOCurrencies;
use Money\Exchange\FixedExchange;
use Tfc\ExchangeRate\ExchangeRateFacade;
use Tfc\ExchangeRate\ExchangeRateFacadeInterface;
use Tfc\ExchangeRate\ExchangeRateFactory;
use Tfc\FeeCalculator\Calculator\TransactionFeeCalculatorInterface;
use Tfc\FeeCalculator\Calculator\TransactionFeeCalculator;
use Tfc\PaymentCard\PaymentCardFacade;
use Tfc\PaymentCard\PaymentCardFacadeInterface;
use Tfc\PaymentCard\PaymentCardFactory;

class FeeCalculatorFactory
{
    public function __construct(
        protected array $config
    ) {}

    protected function getConfig(): array
    {
        return $this->config;
    }

    public function createTransactioFeeCalculator(): TransactionFeeCalculatorInterface
    {
        return new TransactionFeeCalculator(
            $this->getConfig(),
            $this->provideExchangeRateFacade(),
            $this->providePaymentCardFacade(),
            $this->createCurrencyConverter(),
        );
    }

    public function createCurrencyConverter(): Converter
    {
        return new Converter(
            new ISOCurrencies(),
            new FixedExchange([]),
        );
    }

    protected function provideExchangeRateFacade(): ExchangeRateFacadeInterface
    {
        return new ExchangeRateFacade(
            new ExchangeRateFactory(),
        );
    }

    protected function providePaymentCardFacade(): PaymentCardFacadeInterface
    {
        return new PaymentCardFacade(
            new PaymentCardFactory(),
        );
    }
}