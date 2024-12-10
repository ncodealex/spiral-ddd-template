<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Events\Catalog\Group;
class GroupWasUpdateSort extends GroupEvent
{
    /**
     * @param string $id
     * @param int    $sort
     */
    public function __construct(
        string     $id,
        public int $sort
    )
    {
        parent::__construct($id, self::UPDATE_SORT);
    }

    public function payload(): array
    {
        return [
            'sort' => $this->sort,
        ];
    }
}
