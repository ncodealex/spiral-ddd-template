<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Events\Catalog\Unit;

class UnitWasUpdateName extends UnitEvent
{
    /**
     * @param string $id
     * @param string $name
     */
    public function __construct(
        string        $id,
        public string $name
    )
    {
        parent::__construct($id, self::UPDATE_NAME);
    }

    public function payload(): array
    {
        return [
            'name' => $this->name
        ];
    }
}
