<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\Traits;

use Cycle\Annotated\Annotation\Column;
use Showrent\Share\Domain\ValueObject\Description;

/** @deprecated */
trait HasDescriptionTrait
{
    #[Column(type: 'text', nullable: false, typecast: Description::class)]
    /**
     * @psalm-suppress MissingConstructor
     */
    protected Description $description;

    public function getDescription(): Description
    {
        return $this->description;
    }

    public function setDescription(Description $description): self
    {
        $this->description = $description;
        return $this;
    }


}
