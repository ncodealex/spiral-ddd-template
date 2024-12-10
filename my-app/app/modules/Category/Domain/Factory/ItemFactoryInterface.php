<?php
declare(strict_types=1);

namespace Modules\Category\Domain\Factory;

use Modules\Category\Domain\Entity\Item;
use Shared\Domain\ValueObject\Category\CategoryOwner;
use Shared\Domain\ValueObject\Name;

interface ItemFactoryInterface
{
    public function create(
        Name          $name,
        CategoryOwner $owner = CategoryOwner::DEFAULT,
        ?string       $placeholder = null,
        ?string       $comment = null,
        bool          $isDefault = false,
        int           $sort = 0
    ): Item;
}
