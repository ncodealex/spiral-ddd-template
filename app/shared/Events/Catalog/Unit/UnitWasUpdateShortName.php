<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Events\Catalog\Unit;

class UnitWasUpdateShortName extends UnitEvent
{
    /**
     * @param string $id
     * @param string $shortName
     */
    public function __construct(
        string        $id,
        public string $shortName,
    )
    {
        parent::__construct($id, self::UPDATE_SHORT_NAME);
    }

    public function payload(): array
    {
        return [
            'shortName' => $this->shortName
        ];
    }
}
