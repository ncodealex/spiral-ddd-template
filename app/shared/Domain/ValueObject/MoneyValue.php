<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\ValueObject;


use Money\Currency;
use Money\Money;
use Shared\Domain\Validation\AppAssertLazy;

readonly final class MoneyValue implements ValueObjectInterface
{
    private function __construct(
        private Money $value
    )
    {
    }

    public static function create(mixed $value, ?string $propertyPath = null): self
    {
        AppAssertLazy::lazy()
            ->that($value, $propertyPath)
            ->isArray()
            ->keyExists(0)
            ->keyExists(1)
            ->verifyNow();
        return new self(new Money($value[0], new Currency($value[1])));
    }

    public function getValue(): Money
    {
        return $this->value;
    }
}
