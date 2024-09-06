<?php

namespace Tfc\FeeCalculator\Calculator;

use Money\Currency;
use Money\Money;
use Tfc\DataModel\Transaction;

interface TransactionFeeCalculatorInterface
{
    public function calculateFee(Transaction $transaction, Currency $feeCurrency): Money;
}