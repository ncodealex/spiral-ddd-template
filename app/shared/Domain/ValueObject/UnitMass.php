<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\ValueObject;

/**
 * Group of mass
 */
final class UnitMass extends BaseUnit
{
    protected string $name = 'mass';

    protected int $precision = 3;
    protected string $worldCode = 'KGM';
    protected string $worldName = 'kg';

    /**
     * @inheritDoc
     */
    public static function create(mixed $value, ?string $propertyPath = null): self
    {
        return new self((int)$value);
    }
}
