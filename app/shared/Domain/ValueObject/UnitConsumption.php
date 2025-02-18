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
final class UnitConsumption extends BaseUnit
{
    protected string $name = 'consumption';
    protected int $precision = 2;
    protected string $worldCode = 'AMP';
    protected string $worldName = 'A';

    /**
     * @inheritDoc
     */
    public static function create(mixed $value, ?string $propertyPath = null): self
    {
        return new self((int)$value);
    }
}
