<?php

namespace Tfc\DataSource\Mapper;

use Tfc\DataModel\AbstractDto;

interface DataSourceMapperInterface
{
    public function mapToModel(array $data): AbstractDto;
}