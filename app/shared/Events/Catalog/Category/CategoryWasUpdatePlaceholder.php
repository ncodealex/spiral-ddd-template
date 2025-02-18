<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Events\Catalog\Category;

class CategoryWasUpdatePlaceholder extends CategoryEvent
{
    /**
     * @param string $id
     * @param string $placeholder
     */
    public function __construct(
        string        $id,
        public string $placeholder
    )
    {
        parent::__construct($id, self::UPDATE_PLACEHOLDER);
    }

    public function payload(): array
    {
        return [
            'placeholder' => $this->placeholder
        ];
    }
}
