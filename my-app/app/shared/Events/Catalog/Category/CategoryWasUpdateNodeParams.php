<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Events\Catalog\Category;

class CategoryWasUpdateNodeParams extends CategoryEvent
{
    /**
     * @param string $id
     * @param int    $left
     * @param int    $right
     * @param int    $depth
     */
    public function __construct(
        string     $id,
        public int $left,
        public int $right,
        public int $depth
    )
    {
        parent::__construct($id, self::UPDATE_NODE_PARAMS);
    }

    public function payload(): array
    {
        return [
            'left' => $this->left,
            'right' => $this->right,
            'depth' => $this->depth
        ];
    }
}
