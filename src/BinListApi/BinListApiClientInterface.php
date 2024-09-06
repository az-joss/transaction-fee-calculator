<?php

namespace Tfc\BinListApi;

interface BinListApiClientInterface
{
    public function getCardIssuer(string $binCode): array;
}