<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Events\Catalog\Category;

class CategoryWasSetDefault extends CategoryEvent
{
    /**
     * @param string $id
     */
    public function __construct(
        string $id
    )
    {
        parent::__construct($id, self::SET_DEFAULT);
    }

    public function payload(): array
    {
        return [];
    }
}
