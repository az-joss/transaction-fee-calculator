<?php

namespace Tfc\DataSource\Reader;

use Generator;

interface DataSourceReaderInterface
{
    /**
     * @param string $path
     * @return \Generator<\Tfc\DataModel\Transaction>
     */
    public function read(string $path): Generator;
}