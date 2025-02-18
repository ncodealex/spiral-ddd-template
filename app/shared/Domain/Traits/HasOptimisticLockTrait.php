<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Showrent\Share\Domain\Traits;

use Cycle\Annotated\Annotation\Column;

trait HasOptimisticLockTrait
{
    /**
     * @psalm-suppress MissingConstructor
     */
    #[Column(type: 'integer', default: 1, unsigned: true)]
    private int $version;


    public function getVersion(): int
    {
        return $this->version;
    }

    public function setVersion(int $version): void
    {
        $this->version = $version;
    }
}
