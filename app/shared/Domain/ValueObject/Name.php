<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\ValueObject;

use Shared\Domain\Validation\AppAssertLazy;
use Stringable;

readonly final class Name implements ValueObjectInterface, Stringable
{
    private function __construct(
        private string $value,
    )
    {
    }

    public static function create(mixed $value, ?string $propertyPath = null): self
    {
        AppAssertLazy::lazy()
            ->that($value, $propertyPath)
            ->notEmpty()
            ->string()
            ->minLength(3)
            ->maxLength(255)
            ->verifyNow();

        return new self((string)$value);
    }

    public function isEqualString(string $name): bool
    {
        return $this->value === $name;
    }

    public function isEqual(?self $name = null): bool
    {
        return $this->value === $name?->value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
