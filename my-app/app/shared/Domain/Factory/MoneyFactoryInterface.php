<?php

namespace Shared\Domain\Factory;

use Money\Money;

interface MoneyFactoryInterface
{
    public function create(int $amount, ?string $currency = null): Money;
}
