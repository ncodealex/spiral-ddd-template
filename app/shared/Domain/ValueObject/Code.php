<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\ValueObject;


use Shared\Domain\Validation\AppAssertLazy;

readonly final class Code implements ValueObjectInterface
{
    private function __construct(
        private string $value,
    )
    {
    }

    public static function generate(): self
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return new self(substr(str_shuffle($permitted_chars), 0, 10));
    }

    public static function create(mixed $value, ?string $propertyPath = null): self
    {
        AppAssertLazy::lazy()
            ->that($value, $propertyPath)
            ->notEmpty()
            ->string()
            ->minLength(3)
            ->maxLength(10)
            ->verifyNow();

        return new self((string)$value);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
