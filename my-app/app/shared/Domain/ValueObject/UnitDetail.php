<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\ValueObject;

use JsonSerializable;

readonly class UnitDetail implements JsonSerializable
{

    protected function __construct(
        protected int    $value = 0,
        protected string $name = 'size',
        protected int    $precision = 2,
        protected string $worldCode = 'CMT',
        protected string $worldName = 'cm'
    )
    {
    }

    public static function create(int $value, string $name, int $precision, string $worldCode, string $worldName): UnitDetail
    {
        return new static($value, $name, $precision, $worldCode, $worldName);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
            'precision' => $this->precision,
            'worldCode' => $this->worldCode,
            'worldName' => $this->worldName,
        ];
    }
}
