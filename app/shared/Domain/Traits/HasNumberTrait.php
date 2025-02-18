<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Showrent\Share\Domain\Traits;

use Cycle\Annotated\Annotation\Column;
use Showrent\Share\Domain\ValueObject\Number;


trait HasNumberTrait
{
    #[Column(type: 'string(10)', nullable: false, default: false, typecast: Number::class)]
    /**
     * @psalm-suppress MissingConstructor
     */
    protected Number $number;

    public function getNumber(): Number
    {
        return $this->number;
    }

    public function setNumber(Number $number): self
    {
        $this->number = $number;
        return $this;
    }

    public function setNumberFromString(string $number): self
    {
        $this->number = Number::create($number, 'number');
        return $this;
    }


}
