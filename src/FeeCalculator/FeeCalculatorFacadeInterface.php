<?php

namespace Tfc\FeeCalculator;

use Money\Currency;
use Money\Money;
use Tfc\DataModel\Transaction;

interface FeeCalculatorFacadeInterface
{
    public function calculateTransactionFee(Transaction $transaction, Currency $feeCurrency): Money;
}