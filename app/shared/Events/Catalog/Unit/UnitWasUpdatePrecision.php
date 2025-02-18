<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Events\Catalog\Unit;

class UnitWasUpdatePrecision extends UnitEvent
{
    /**
     * @param string $id
     * @param int    $precision
     */
    public function __construct(
        string     $id,
        public int $precision,
    )
    {
        parent::__construct($id, self::UPDATE_PRECISION);
    }

    public function payload(): array
    {
        return [
            'precision' => $this->precision
        ];
    }
}
