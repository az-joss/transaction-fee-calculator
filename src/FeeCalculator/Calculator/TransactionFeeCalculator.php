<?php

namespace Tfc\FeeCalculator\Calculator;

use Money\Converter;
use Money\Currency;
use Money\Money;
use Tfc\DataModel\Transaction;
use Tfc\ExchangeRate\ExchangeRateFacadeInterface;
use Tfc\FeeCalculator\FeeCalculatorConst;
use Tfc\PaymentCard\PaymentCardFacadeInterface;

class TransactionFeeCalculator implements TransactionFeeCalculatorInterface
{
    protected array $euCounties = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];

    public function __construct(
        protected array $config,
        protected ExchangeRateFacadeInterface $exchangeRateFacade,
        protected PaymentCardFacadeInterface $paymentCardFacade,
        protected Converter $currencyConverter
    ) {}

    public function calculateFee(Transaction $transaction, Currency $feeCurrency): Money
    {
        $amount = $transaction->getAmount();

        if (!$amount->getCurrency()->equals($feeCurrency)) {
            $exchangePair = $this->exchangeRateFacade->getExchangePair($amount->getCurrency(), $feeCurrency);
            $amount = $this->currencyConverter->convertAgainstCurrencyPair($amount, $exchangePair);
        }
        
        $allocations = $this->getAllocation($this->getFeeRate($transaction));

        return $amount->allocate($allocations)['fee'];
    }

    protected function getFeeRate($transaction): float
    {
        $cardIsuer = $this->paymentCardFacade
            ->getPaymentCardIssuer($transaction->getBinCode());
        $countryCode = $cardIsuer->getCountry()->getAlpha2();

        return in_array($countryCode, $this->euCounties)
            ? $this->config[FeeCalculatorConst::KEY_FEE_EU]
            : $this->config[FeeCalculatorConst::KEY_FEE_NON_EU];
    }

    protected function getAllocation(float $rate): array
    {
        $ratePercentage = $rate * 100;
        $restPercentage = 100 - $ratePercentage;

        return [
            'fee' => $ratePercentage,
            'finalAmount' => $restPercentage,
        ];
    }
}