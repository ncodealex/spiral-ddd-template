<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Events\Catalog\Group;

class GroupWasUpdateColor extends GroupEvent
{
    /**
     * @param string $id
     * @param string $color
     */
    public function __construct(
        string        $id,
        public string $color
    )
    {
        parent::__construct($id, self::UPDATE_COLOR);
    }

    public function payload(): array
    {
        return [
            'color' => $this->color
        ];
    }
}
