<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\ValueObject;


abstract class BaseUnit implements ValueObjectInterface
{
    protected string $name = 'size';
    protected int $precision = 2;
    protected string $worldCode = 'CMT';

    protected string $worldName = 'cm';

    protected function __construct(
        protected readonly int $value = 0,
    )
    {

    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getDetail(): UnitDetail
    {
        return UnitDetail::create(
            $this->value,
            $this->name,
            $this->precision,
            $this->worldCode,
            $this->worldName
        );
    }


}
