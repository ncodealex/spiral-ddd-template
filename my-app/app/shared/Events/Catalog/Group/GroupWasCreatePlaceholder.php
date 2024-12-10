<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Events\Catalog\Group;

class GroupWasCreatePlaceholder extends GroupEvent
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
        parent::__construct($id, self::CREATE_PLACEHOLDER);
    }

    public function payload(): array
    {
        return [
            'placeholder' => $this->placeholder
        ];
    }
}
