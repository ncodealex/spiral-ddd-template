<?php
declare(strict_types=1);

namespace Modules\Brand\Infrastructure\CycleORM\Factory;

use Cycle\ORM\ORMInterface;
use Modules\Brand\Domain\Entity\Item;
use Modules\Brand\Domain\Factory\ItemFactoryInterface;
use Shared\Domain\ValueObject\Brand\BrandOwner;
use Shared\Domain\ValueObject\Name;

final readonly class ItemFactory implements ItemFactoryInterface
{

    public function __construct(
        private ORMInterface $orm
    )
    {
    }

    public function create(
        BrandOwner $owner,
        Name       $name,
        ?string    $comment = null,
        ?string    $skuProperty = null,
        ?string    $country = null,
        bool       $isDefault = false,
        ?string    $avatarId = null
    ): Item
    {
        return $this->orm->make(Item::class, [
            Item::F_OWNER => $owner,
            Item::F_NAME => $name,
            Item::F_COMMENT => $comment,
            Item::F_SKU_PROPERTY => $skuProperty,
            Item::F_COUNTRY => $country,
            Item::F_IS_DEFAULT => $isDefault,
            Item::F_AVATAR_ID => $avatarId
        ]);
    }
}
