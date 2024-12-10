<?php

namespace Shared\Application\Factory;

use Money\Currency;
use Money\Money;
use Shared\Domain\Factory\DefaultCurrencyInterface;
use Shared\Domain\Factory\MoneyFactoryInterface;

final readonly class MoneyFactory implements MoneyFactoryInterface
{

    public function __construct(
        private DefaultCurrencyInterface $defaultCurrency
    )
    {
    }

    public function create(int $amount, ?string $currency = null): Money
    {
        if ($currency === null) {
            $currency = $this->defaultCurrency->getCurrency();
        }
        return new Money($amount, new Currency($currency));
    }
}
