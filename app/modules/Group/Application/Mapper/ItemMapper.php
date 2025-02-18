<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Group\Application\Mapper;

use Modules\Group\Domain\Entity\Item;
use Modules\Group\Domain\Factory\ItemFactoryInterface;
use Shared\Domain\ValueObject\Color;
use Shared\Domain\ValueObject\Uuid;
use SharedCQRS\Group\Command\GroupPatchCommand;
use SharedCQRS\Group\Command\GroupSaveCommand;
use SharedCQRS\Group\GroupDTO;

final readonly class ItemMapper
{
    public static function toDTO(Item $entity): GroupDTO
    {
        return new GroupDTO(
            $entity->getId(),
            $entity->getName(),
            $entity->getOwner(),
            $entity->getColor(),
            $entity->getComment(),
            $entity->getPlaceholder(),
            $entity->getIsSystem(),
            $entity->isDefault(),
            $entity->getSort(),
            $entity->getCreatedAt(),
            $entity->getUpdatedAt(),
            $entity->getDeletedAt(),
            $entity->getVersion()
        );
    }

    public static function toEntity(ItemFactoryInterface $factory, GroupSaveCommand $command): Item
    {
        $entity = $factory->create(
            $command->name,
            $command->owner,
            $command->color,
            $command->comment,
            $command->placeholder,
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

    public static function putEntity(Item $entity, GroupSaveCommand $command): Item
    {

        if ($command->color !== null) {
            $entity->setColor($command->color);
        }

        $color = $command->color ?? Color::create(Item::DEFAULT_COLOR);

        $entity->setName($command->name);
        $entity->setOwner($command->owner);
        $entity->setColor($color);
        $entity->setComment($command->comment);
        $entity->setPlaceholder($command->placeholder);
        $entity->setIsDefault($command->isDefault);
        $entity->setSort($command->sort);
        return $entity;
    }

    public static function updateEntity(Item $entity, GroupPatchCommand $command): Item
    {
        /** @psalm-suppress MixedAssignment */
        foreach (get_object_vars($command) as $property => $value) {
            if ($value !== null) {
                /** @psalm-suppress MixedArgument */
                match ($property) {
                    Item::F_NAME => $entity->setName($value),
                    Item::F_OWNER => $entity->setOwner($value),
                    Item::F_COLOR => $entity->setColor($value),
                    Item::F_COMMENT => $entity->setComment($value),
                    Item::F_PLACEHOLDER => $entity->setPlaceholder($value),
                    Item::F_IS_DEFAULT => $entity->setIsDefault($value),
                    Item::F_SORT => $entity->setSort($value),
                    default => null,
                };
            }
        }
        return $entity;
    }


}
