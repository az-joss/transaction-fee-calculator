<?php

namespace Tfc\DataModel;

use Money\Money;

class Transaction extends AbstractDto
{
    protected string $binCode;

    protected Money $amount;

    public function getBinCode(): ?string
    {
        return $this->binCode;
    }

    public function setBinCode(?string $binCode): self
    {
        $this->binCode = $binCode;

        return $this;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function setAmount(?Money $amount): self
    {
        $this->amount = $amount;

        return $this;
    }  
}