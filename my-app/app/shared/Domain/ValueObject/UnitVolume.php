<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\ValueObject;


/**
 * Group of Volume
 */
final class UnitVolume extends BaseUnit
{
    protected string $name = 'volume';

    protected int $precision = 3;
    protected string $worldCode = 'MTQ';

    protected string $worldName = 'm3';

    /**
     * @inheritDoc
     */
    public static function create(mixed $value, ?string $propertyPath = null): self
    {
        return new self((int)$value);
    }

    public static function createFromSize(UnitLength $l, UnitLength $w, UnitLength $h): self
    {
        $value = ($l->getValue() * $w->getValue() * $h->getValue()) / (pow(10, 3));

        return new self((int)$value);
    }
}
