<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Showrent\Share\Domain\Traits;

use Cycle\Annotated\Annotation\Column;
use Showrent\Share\Domain\ValueObject\Name;


trait HasNameTrait
{
    #[Column(type: 'string', nullable: false, default: false, typecast: Name::class)]
    /**
     * @psalm-suppress MissingConstructor
     */
    protected Name $name;

    public function getName(): Name
    {
        return $this->name;
    }

    public function setName(mixed $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setNameFromString(string $name): self
    {
        $this->name = Name::create($name, 'name');
        return $this;
    }


}
