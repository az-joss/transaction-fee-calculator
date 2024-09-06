<?php

namespace Tfc\DataSource;

use Money\Currencies\ISOCurrencies;
use Money\MoneyParser;
use Money\Parser\DecimalMoneyParser;
use Tfc\DataSource\Mapper\DataSourceMapperInterface;
use Tfc\DataSource\Mapper\TransactionDataSourceMapper;
use Tfc\DataSource\Reader\DataSourceReaderInterface;
use Tfc\DataSource\Reader\JsonRowTextDataSourceReader;

class DataSourceFactory
{
    public function createDataSourceReader(): DataSourceReaderInterface
    {
        return new JsonRowTextDataSourceReader(
            $this->createTransactionDataSourceMapper(),
        );
    }

    public function createTransactionDataSourceMapper(): DataSourceMapperInterface
    {
        return new TransactionDataSourceMapper(
            $this->createMoneyParser(),
        );
    }

    public function createMoneyParser(): MoneyParser
    {
        return new DecimalMoneyParser(
            new ISOCurrencies(),
        );
    }
}