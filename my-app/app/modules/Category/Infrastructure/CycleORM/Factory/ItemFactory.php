<?php
declare(strict_types=1);

namespace Modules\Category\Infrastructure\CycleORM\Factory;

use Cycle\ORM\ORMInterface;
use Modules\Category\Domain\Entity\Item;
use Modules\Category\Domain\Factory\ItemFactoryInterface;
use Shared\Domain\ValueObject\Category\CategoryOwner;
use Shared\Domain\ValueObject\Name;

final readonly class ItemFactory implements ItemFactoryInterface
{

    public function __construct(
        private ORMInterface $orm
    )
    {
    }

    public function create(
        Name          $name,
        CategoryOwner $owner = CategoryOwner::DEFAULT,
        ?string       $placeholder = null,
        ?string       $comment = null,
        bool          $isDefault = false,
        int           $sort = 0
    ): Item
    {
        return $this->orm->make(Item::class, [
            'name' => $name,
            'owner' => $owner,
            'placeholder' => $placeholder,
            'comment' => $comment,
            'isDefault' => $isDefault,
            'sort' => $sort
        ]);
    }
}
