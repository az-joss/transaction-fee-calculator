<?php

namespace Tfc\DataModel;

use Money\Currency;

class Country extends AbstractDto
{   
    protected ?string $alpha2;

    protected ?string $name;

    protected ?Currency $currency;

    public function getAlpha2(): ?string
    {
        return $this->alpha2;
    }

    public function setAlpha2(?string $alpha2): self
    {
        $this->alpha2 = $alpha2;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}