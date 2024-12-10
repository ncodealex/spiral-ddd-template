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

readonly final class Color implements ValueObjectInterface, Stringable
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
            ->regex('/^#[0-9a-fA-F]{6}$/', 'Invalid color format')
            ->verifyNow();

        return new self((string)$value);
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
