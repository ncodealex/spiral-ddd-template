<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\ValueObject;


/**
 * Group of power
 */
final class UnitPower extends BaseUnit
{
    protected string $name = 'power';

    protected int $precision = 3;
    protected string $worldCode = 'WTT';

    protected string $worldName = 'W';

    /**
     * @inheritDoc
     */
    public static function create(mixed $value, ?string $propertyPath = null): self
    {
        return new self((int)$value);
    }
}
