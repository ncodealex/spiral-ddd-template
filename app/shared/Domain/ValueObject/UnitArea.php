<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\ValueObject;

/**
 * Group of area ( square meter )
 */
final class UnitArea extends BaseUnit
{
    protected string $name = 'area';

    protected int $precision = 3;
    protected string $worldCode = 'MTK';
    protected string $worldName = 'm2';

    /**
     * @inheritDoc
     */

    public static function create(mixed $value, ?string $propertyPath = null): self
    {
        return new self((int)$value);
    }
}
