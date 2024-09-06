<?php

namespace Tfc\DataSource;

use Generator;

interface DataSourceFacadeInterface
{
    /**
     * @param string $path
     * @return \Generator<\Tfc\DataModel\Transaction>
     */
    public function readSource(string $path): Generator;
}