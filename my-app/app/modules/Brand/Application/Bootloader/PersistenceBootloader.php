<?php

namespace Modules\Brand\Application\Bootloader;

use Cycle\ORM\EntityManagerInterface;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select;
use Modules\Brand\Domain\Entity\Item;
use Modules\Brand\Domain\Factory\ItemFactoryInterface;
use Modules\Brand\Domain\Repository\ItemRepositoryInterface;
use Modules\Brand\Domain\Specification\ItemIsExistSpecificationInterface;
use Modules\Brand\Infrastructure\CycleORM\Factory\ItemFactory;
use Modules\Brand\Infrastructure\CycleORM\Repository\ItemRepository;
use Modules\Brand\Infrastructure\CycleORM\Specification\ItemIsExistSpecification;
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
                ORMInterface           $orm,
                EntityManagerInterface $em
            ) => new ItemRepository(new Select($orm, Item::class)),

            ItemFactoryInterface::class => ItemFactory::class,
            ItemIsExistSpecificationInterface::class => ItemIsExistSpecification::class,
        ];
    }
}
