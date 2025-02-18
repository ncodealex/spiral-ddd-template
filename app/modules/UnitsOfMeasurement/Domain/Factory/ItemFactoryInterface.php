<?php
declare(strict_types=1);

namespace Modules\UnitsOfMeasurement\Domain\Factory;

use Modules\UnitsOfMeasurement\Domain\Entity\Item;
use Shared\Domain\ValueObject\PackagingType;

interface ItemFactoryInterface
{
    public function create(
        string        $name,
        int           $precision = 0,
        PackagingType $forPackaging = PackagingType::NOT_USED,
        bool          $isDefault = false,
    ): Item;
}
