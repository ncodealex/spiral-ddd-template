<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\ValueObject;


interface ValueObjectInterface
{
    /** Create value */
    public static function create(mixed $value, ?string $propertyPath = null): self;

    /** Get value */
    public function getValue(): mixed;
}
