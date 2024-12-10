<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\Traits;

use Cycle\Annotated\Annotation\Column;
use Shared\Domain\ValueObject\Color;
use Showrent\Share\Domain\ValueObject\Color;


/** @deprecated */
trait HasColorTrait
{
    public const DEFAULT_COLOR = '#0080ff';

    #[Column(type: 'string(7)', nullable: false, default: self::DEFAULT_COLOR, typecast: Color::class)]
    /**
     * @psalm-suppress MissingConstructor
     */
    protected string $color;

    public function getColor(): Color
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = Color::create($color, 'color');
        return $this;
    }


}
