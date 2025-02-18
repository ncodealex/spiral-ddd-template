<?php

namespace Modules\UnitsOfMeasurement\Application\Bootloader;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select;
use Modules\UnitsOfMeasurement\Domain\Entity\Item;
use Modules\UnitsOfMeasurement\Domain\Factory\ItemFactoryInterface;
use Modules\UnitsOfMeasurement\Domain\Repository\ItemRepositoryInterface;
use Modules\UnitsOfMeasurement\Domain\Specification\ItemIsExistSpecificationInterface;
use Modules\UnitsOfMeasurement\Infrastructure\CycleORM\Factory\ItemFactory;
use Modules\UnitsOfMeasurement\Infrastructure\CycleORM\Repository\ItemRepository;
use Modules\UnitsOfMeasurement\Infrastructure\CycleORM\Specification\ItemIsExistSpecification;
use Spiral\Boot\Bootloader\Bootloader;

/**
 * Simple bootloaders that registers Domain repositories.
 */
class PersistenceBootloader extends Bootloader
{
    public function defineSingletons(): array
    {
        return [
            ItemRepositoryInterface::class => static fn (
                ORMInterface $orm
            ) => new ItemRepository(new Select($orm, Item::class)),

            ItemFactoryInterface::class => ItemFactory::class,
            ItemIsExistSpecificationInterface::class => ItemIsExistSpecification::class,
        ];
    }
}
