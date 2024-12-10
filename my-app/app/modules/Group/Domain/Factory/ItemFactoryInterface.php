<?php
declare(strict_types=1);

namespace Modules\Group\Domain\Factory;

use Modules\Group\Domain\Entity\Item;
use Shared\Domain\ValueObject\Color;
use Shared\Domain\ValueObject\Group\GroupOwner;
use Shared\Domain\ValueObject\Name;

interface ItemFactoryInterface
{
    public function create(
        Name       $name,
        GroupOwner $owner = GroupOwner::DEFAULT,
        ?Color     $color = null,
        ?string    $comment = null,
        ?string    $placeholder = null,
        bool       $isDefault = false,
        int        $sort = 0,
    ): Item;
}
