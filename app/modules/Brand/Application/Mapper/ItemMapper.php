<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Brand\Application\Mapper;

use Modules\Brand\Domain\Entity\Item;
use Modules\Brand\Domain\Factory\ItemFactoryInterface;
use Shared\Domain\ValueObject\Name;
use Shared\Domain\ValueObject\Uuid;
use SharedCQRS\Brand\BrandDTO;
use SharedCQRS\Brand\Command\BrandPatchCommand;
use SharedCQRS\Brand\Command\BrandSaveCommand;

final readonly class ItemMapper
{
    public static function toDTO(Item $entity): BrandDTO
    {
        return new BrandDTO(
            $entity->getId(),
            $entity->getOwner(),
            $entity->getName(),
            $entity->getComment(),
            $entity->isSystem(),
            $entity->isDefault(),
            $entity->getSkuProperty(),
            $entity->getCountry(),
            $entity->getAvatarId(),
            $entity->getSort(),
            $entity->getCreatedAt(),
            $entity->getUpdatedAt(),
            $entity->getDeletedAt(),
            $entity->getVersion()
        );
    }

    public static function toEntity(ItemFactoryInterface $factory, BrandSaveCommand $command): Item
    {
        $entity = $factory->create(
            $command->owner,
            $command->name,
            $command->comment,
            $command->skuProperty,
            $command->country,
            $command->isDefault,
            $command->avatarId
        );

        if ($command->id === null) {
            $entity->setId(Uuid::generate());
        } else {
            $entity->setId($command->id);
        }

        return $entity;
    }

    public static function putEntity(Item $entity, BrandSaveCommand $command): Item
    {
        $entity->setOwner($command->owner);
        $entity->setName($command->name);
        $entity->setComment($command->comment);
        $entity->setIsDefault($command->isDefault);
        $entity->setSkuProperty($command->skuProperty);
        $entity->setCountry($command->country);
        $entity->setAvatarId($command->avatarId);
        $entity->setSort($command->sort);
        return $entity;
    }

    public static function updateEntity(Item $entity, BrandPatchCommand $command): Item
    {
        foreach (get_object_vars($command) as $property => $value) {
            if ($value !== null) {
                /** @psalm-suppress InvalidCast */
                match ($property) {
                    /** @var $value Name */
                    Item::F_NAME => $entity->setName($value),
                    /** @var $value string */
                    Item::F_COMMENT => $entity->setComment((string)$value),
                    /** @var $value string */
                    Item::F_IS_DEFAULT => $entity->setIsDefault((bool)$value),
                    /** @var $value string */
                    Item::F_SKU_PROPERTY => $entity->setSkuProperty((string)$value),
                    /** @var $value string */
                    Item::F_COUNTRY => $entity->setCountry((string)$value),
                    /** @var $value string */
                    Item::F_AVATAR_ID => $entity->setAvatarId((string)$value),
                    /** @var $value int */
                    Item::F_SORT => $entity->setSort((int)$value),
                    default => null,
                };
            }
        }
        return $entity;
    }
}
