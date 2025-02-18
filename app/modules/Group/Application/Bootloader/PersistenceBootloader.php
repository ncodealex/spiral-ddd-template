<?php

namespace Modules\Group\Application\Bootloader;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select;
use Modules\Group\Domain\Entity\Item;
use Modules\Group\Domain\Factory\ItemFactoryInterface;
use Modules\Group\Domain\Repository\ItemRepositoryInterface;
use Modules\Group\Domain\Specification\ItemIsExistSpecificationInterface;
use Modules\Group\Infrastructure\CycleORM\Factory\ItemFactory;
use Modules\Group\Infrastructure\CycleORM\Repository\ItemRepository;
use Modules\Group\Infrastructure\CycleORM\Specification\ItemIsExistSpecification;
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
