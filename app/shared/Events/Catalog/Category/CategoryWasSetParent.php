<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Events\Catalog\Category;

class CategoryWasSetParent extends CategoryEvent
{
    /**
     * @param string $id
     * @param string $parentId
     */
    public function __construct(
        string        $id,
        public string $parentId
    )
    {
        parent::__construct($id, self::SET_PARENT);
    }

    public function payload(): array
    {
        return [
            'parentId' => $this->parentId,
        ];
    }
}
