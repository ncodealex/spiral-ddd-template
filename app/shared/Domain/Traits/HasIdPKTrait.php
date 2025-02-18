<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Showrent\Share\Domain\Traits;

use Cycle\Annotated\Annotation\Column;

trait HasIdPKTrait
{
    #[Column(type: 'bigPrimary')]
    /**
     * @psalm-suppress MissingConstructor
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private int $id;

    public function getId(): int
    {
        return $this->id;
    }
}
