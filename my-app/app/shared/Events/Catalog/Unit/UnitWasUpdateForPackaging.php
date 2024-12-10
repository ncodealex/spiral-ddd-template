<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Events\Catalog\Unit;

class UnitWasUpdateForPackaging extends UnitEvent
{
    /**
     * @param string $id
     * @param string $forPackaging
     */
    public function __construct(
        string        $id,
        public string $forPackaging,
    )
    {
        parent::__construct($id, self::UPDATE_FOR_PACKAGING);
    }

    public function payload(): array
    {
        return [
            'forPackaging' => $this->forPackaging,
        ];
    }
}
