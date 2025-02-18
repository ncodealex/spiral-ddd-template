<?php
declare(strict_types=1);

namespace Modules\Group\Infrastructure\CycleORM\Factory;

use Cycle\ORM\ORMInterface;
use Modules\Group\Domain\Entity\Item;
use Modules\Group\Domain\Factory\ItemFactoryInterface;
use Shared\Domain\ValueObject\Color;
use Shared\Domain\ValueObject\Group\GroupOwner;
use Shared\Domain\ValueObject\Name;

final readonly class ItemFactory implements ItemFactoryInterface
{

    public function __construct(
        private ORMInterface $orm
    )
    {
    }

    public function create(
        Name       $name,
        GroupOwner $owner = GroupOwner::DEFAULT,
        ?Color     $color = null,
        ?string    $comment = null,
        ?string    $placeholder = null,
        bool       $isDefault = false,
        int        $sort = 0,
    ): Item
    {
        if ($color === null) {
            $color = Color::create(Item::DEFAULT_COLOR);
        }

        return $this->orm->make(Item::class, [
            Item::F_NAME => $name,
            Item::F_OWNER => $owner,
            Item::F_COLOR => $color,
            Item::F_COMMENT => $comment,
            Item::F_PLACEHOLDER => $placeholder,
            Item::F_IS_DEFAULT => $isDefault,
            Item::F_SORT => $sort,
        ]);
    }
}
