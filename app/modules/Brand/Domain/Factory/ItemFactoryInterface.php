<?php
declare(strict_types=1);

namespace Modules\Brand\Domain\Factory;

use Modules\Brand\Domain\Entity\Item;
use Shared\Domain\ValueObject\Brand\BrandOwner;
use Shared\Domain\ValueObject\Name;

interface ItemFactoryInterface
{
    public function create(
        BrandOwner $owner,
        Name       $name,
        ?string    $comment = null,
        ?string    $skuProperty = null,
        ?string    $country = null,
        bool       $isDefault = false,
        ?string    $avatarId = null
    ): Item;
}
