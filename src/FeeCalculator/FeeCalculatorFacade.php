<?php

namespace Tfc\FeeCalculator;

use Money\Currency;
use Money\Money;
use Tfc\DataModel\Transaction;

class FeeCalculatorFacade implements FeeCalculatorFacadeInterface
{
    public function __construct(
        protected FeeCalculatorFactory $factory
    ) {}
    
    public function calculateTransactionFee(Transaction $transaction, Currency $feeCurrency): Money
    {
        return $this->factory->createTransactioFeeCalculator()
            ->calculateFee($transaction, $feeCurrency);
    }
}