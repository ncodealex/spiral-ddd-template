<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Category\Application\Mapper;

use Modules\Category\Domain\Entity\Item;
use Modules\Category\Domain\Factory\ItemFactoryInterface;
use Shared\Domain\ValueObject\Uuid;
use SharedCQRS\Category\CategoryDTO;
use SharedCQRS\Category\Command\CategoryPatchCommand;
use SharedCQRS\Category\Command\CategorySaveCommand;

final readonly class ItemMapper
{
    public static function toDTO(Item $entity): CategoryDTO
    {
        trap($entity);
        return new CategoryDTO(
            $entity->getId(),
            $entity->getName(),
            $entity->getOwner(),
            $entity->getParentId(),
            $entity->getPlaceholder(),
            $entity->getComment(),
            $entity->isSystem(),
            $entity->isDefault(),
            $entity->getSort(),
            $entity->getCreatedAt(),
            $entity->getUpdatedAt(),
            $entity->getDeletedAt(),
            $entity->getVersion()
        );
    }

    public static function toEntity(ItemFactoryInterface $factory, CategorySaveCommand $command): Item
    {
        $entity = $factory->create(
            $command->name,
            $command->owner,
            $command->placeholder,
            $command->comment,
            $command->isDefault,
            $command->sort
        );
        if ($command->id === null) {
            $entity->setId(Uuid::generate());
        } else {
            $entity->setId($command->id);
        }
        return $entity;

    }

    public static function putEntity(Item $entity, CategorySaveCommand $command): Item
    {
        $entity->setName($command->name);
        $entity->setOwner($command->owner);
        $entity->setPlaceholder($command->placeholder);
        $entity->setComment($command->comment);
        $entity->setIsDefault($command->isDefault);
        $entity->setSort($command->sort);

        return $entity;
    }

    public static function updateEntity(Item $entity, CategoryPatchCommand $command): Item
    {
        foreach (get_object_vars($command) as $property => $value) {
            if ($value !== null) {
                match ($property) {
                    Item::F_NAME => $entity->setName($value),
                    Item::F_OWNER => $entity->setOwner($value),
                    Item::F_PLACEHOLDER => $entity->setPlaceholder($value),
                    Item::F_COMMENT => $entity->setComment($value),
                    Item::F_IS_DEFAULT => $entity->setIsDefault($value),
                    Item::F_SORT => $entity->setSort($value),
                    default => null,
                };
            }
        }
        return $entity;
    }


}
