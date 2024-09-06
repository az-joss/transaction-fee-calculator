<?php

require __DIR__ . '/vendor/autoload.php';

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Tfc\DataSource\DataSourceFacade;
use Tfc\DataSource\DataSourceFactory;
use Tfc\FeeCalculator\FeeCalculatorConst;
use Tfc\FeeCalculator\FeeCalculatorFacade;
use Tfc\FeeCalculator\FeeCalculatorFactory;

define('BASE_CURRENCY', getenv('BASE_CURRENCY') ?: 'EUR');

[$command, $filePath] = $argv;

$moneyFormatter = new DecimalMoneyFormatter(new ISOCurrencies());
$dataSourceGenerator = (new DataSourceFacade(new DataSourceFactory()))->readSource($filePath);
$feeCalculator = new FeeCalculatorFacade(
    new FeeCalculatorFactory([
        FeeCalculatorConst::KEY_FEE_EU => 0.01,
        FeeCalculatorConst::KEY_FEE_NON_EU => 0.02,
    ]),
);

$baseCurrency = new Currency(BASE_CURRENCY);

foreach ($dataSourceGenerator as $transaction) {
    $feeAmount = $feeCalculator->calculateTransactionFee($transaction, $baseCurrency);

    printf(
        "Transact %s %10s Fee %6s %s\n",
        $transaction->getAmount()->getCurrency()->getCode(),
        $moneyFormatter->format($transaction->getAmount()),
        $moneyFormatter->format($feeAmount),
        BASE_CURRENCY,
    );
}

return 0;
