<?php

namespace Tfc\DataSource;

use Generator;

class DataSourceFacade implements DataSourceFacadeInterface
{
    public function __construct(
        protected DataSourceFactory $factory
    ) {}

    /**
     * @param string $path
     * @return \Generator<\Tfc\DataModel\Transaction>
     */
    public function readSource(string $path): Generator
    {
        return $this->factory->createDataSourceReader()
            ->read($path);
    }
}