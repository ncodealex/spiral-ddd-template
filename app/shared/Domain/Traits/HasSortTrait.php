<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Showrent\Share\Domain\Traits;

use Cycle\Annotated\Annotation\Column;

trait HasSortTrait
{
    #[Column(type: 'integer', default: 0, unsigned: true)]
    /**
     * @psalm-suppress MissingConstructor
     */
    private int $sort = 1;

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;
        return $this;
    }


}
