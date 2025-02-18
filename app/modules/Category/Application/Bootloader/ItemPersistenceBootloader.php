<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Modules\Category\Application\Bootloader;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select;
use Modules\Category\Domain\Entity\Item;
use Modules\Category\Domain\Factory\ItemFactoryInterface;
use Modules\Category\Domain\Repository\ItemRepositoryInterface;
use Modules\Category\Domain\Specification\ItemIsExistSpecificationInterface;
use Modules\Category\Infrastructure\CycleORM\Factory\ItemFactory;
use Modules\Category\Infrastructure\CycleORM\Repository\ItemRepository;
use Modules\Category\Infrastructure\CycleORM\Specification\ItemIsExistSpecification;
use Spiral\Boot\Bootloader\Bootloader;

/**
 * Simple bootloaders that registers Domain repositories.
 */
class ItemPersistenceBootloader extends Bootloader
{
    public function defineSingletons(): array
    {
        return [
            ItemRepositoryInterface::class => static fn (
                ORMInterface           $orm
            ) => new ItemRepository(new Select($orm, Item::class)),

            ItemFactoryInterface::class => ItemFactory::class,
            ItemIsExistSpecificationInterface::class => ItemIsExistSpecification::class,
        ];
    }
}
