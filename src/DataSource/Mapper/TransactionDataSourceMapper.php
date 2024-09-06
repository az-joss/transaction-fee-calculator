<?php

namespace Tfc\DataSource\Mapper;

use Money\Currency;
use Money\Money;
use Money\MoneyParser;
use Tfc\DataModel\Transaction;

class TransactionDataSourceMapper implements DataSourceMapperInterface
{
    public function __construct(
        protected MoneyParser $moneyParser
    ) {}

    public function mapToModel(array $data): Transaction
    {
        return Transaction::fromArray([
            'binCode' => $data['bin'],
            'amount' => $this->parseMoney($data['amount'], $data['currency']),
        ]);
    }

    public function parseMoney(string $amount, string $currencyCode): Money
    {
        return $this->moneyParser->parse($amount, new Currency($currencyCode));
    }
}