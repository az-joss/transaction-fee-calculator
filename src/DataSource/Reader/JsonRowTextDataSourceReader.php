<?php

namespace Tfc\DataSource\Reader;

use Generator;
use RuntimeException;
use SplFileObject;
use Tfc\DataModel\Transaction;
use Tfc\DataSource\Mapper\DataSourceMapperInterface;

class JsonRowTextDataSourceReader implements DataSourceReaderInterface
{
    public function __construct(
        protected DataSourceMapperInterface $dataSourceMapper
    ) {
        
    }

    /**
     * @param string $path
     * @return \Generator<\Tfc\DataModel\Transaction>
     */
    public function read(string $path): Generator
    {
        $this->assertFileExists($path);

        $file = new SplFileObject($path);
        $file->rewind();

        while (!$file->eof()) {
            $rowString = $file->fgets();

            yield $this->decodeRow($rowString);
        }
    }

    protected function assertFileExists(string $path): void
    {
        if (!file_exists($path)) {
            throw new RuntimeException("File '$path\' is not found.");
        }
    }

    protected function decodeRow(string $rowString): Transaction
    {
        $data = json_decode($rowString, true);

        $model = $this->dataSourceMapper->mapToModel($data);

        return $model;
    }
}