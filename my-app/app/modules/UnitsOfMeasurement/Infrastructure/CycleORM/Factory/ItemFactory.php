<?php
declare(strict_types=1);

namespace Modules\UnitsOfMeasurement\Infrastructure\CycleORM\Factory;

use Cycle\ORM\ORMInterface;
use Modules\UnitsOfMeasurement\Domain\Entity\Item;
use Modules\UnitsOfMeasurement\Domain\Factory\ItemFactoryInterface;
use Shared\Domain\ValueObject\PackagingType;

final readonly class ItemFactory implements ItemFactoryInterface
{

    public function __construct(
        private ORMInterface $orm
    )
    {
    }

    public function create(
        string        $name,
        int           $precision = 0,
        PackagingType $forPackaging = PackagingType::NOT_USED,
        bool          $isDefault = false,
    ): Item
    {
        return $this->orm->make(Item::class, [
            Item::F_NAME => $name,
            Item::F_FOR_PACKAGING => $forPackaging,
            Item::F_PRECISION => $precision,
            Item::F_IS_DEFAULT => $isDefault,
        ]);
    }
}
