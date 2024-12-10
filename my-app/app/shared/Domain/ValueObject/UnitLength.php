<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\ValueObject;

use Shared\Domain\Validation\AppAssertLazy;

/**
 * Group of Length
 */
final class UnitLength extends BaseUnit
{
    protected string $name = 'length';

    protected int $precision = 2;
    protected string $worldCode = 'MMT';

    protected string $worldName = 'mm';

    /**
     * @inheritDoc
     */
    public static function create(mixed $value, ?string $propertyPath = null): self
    {
        AppAssertLazy::lazy()
            ->that($value, $propertyPath)
            ->numeric()
            ->min(1)
            ->verifyNow();
        return new self((int)$value);
    }
}
