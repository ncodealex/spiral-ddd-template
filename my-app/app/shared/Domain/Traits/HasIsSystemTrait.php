<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Showrent\Share\Domain\Traits;

use Cycle\Annotated\Annotation\Column;

trait HasIsSystemTrait
{
    #[Column(type: 'boolean', default: false)]
    /**
     * @psalm-suppress MissingConstructor
     */
    private bool $isSystem = false;

    public function isSystem(): bool
    {
        return $this->isSystem;
    }
}
