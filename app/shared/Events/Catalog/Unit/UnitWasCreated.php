<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Events\Catalog\Unit;


class UnitWasCreated extends UnitEvent
{
    /**
     * @param string $id
     * @param string $name
     * @param string $shortName
     * @param string $forPackaging
     * @param int    $precision
     * @param bool   $isSystem
     * @param bool   $isDefault
     * @param int    $sort
     */
    public function __construct(
        string        $id,
        public string $name,
        public string $shortName,
        public string $forPackaging,
        public int    $precision,
        public bool   $isSystem,
        public bool   $isDefault,
        public int    $sort,
    )
    {
        parent::__construct($id, self::CREATED);
    }

    public function payload(): array
    {
        return [
            'name' => $this->name,
            'shortName' => $this->shortName,
            'forPackaging' => $this->forPackaging,
            'precision' => $this->precision,
            'isSystem' => $this->isSystem,
            'isDefault' => $this->isDefault,
            'sort' => $this->sort,
        ];
    }
}
