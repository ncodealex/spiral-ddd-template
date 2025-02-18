<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Category\Application\Mapper;

use Doctrine\Common\Collections\ArrayCollection;
use Modules\Category\Domain\Entity\Item;
use SharedCQRS\Category\CategoryTreeDTO;

final readonly class ItemTreeMapper
{
    /**
     * @param ArrayCollection<array-key,Item> $entities
     *
     * @return ArrayCollection<array-key,CategoryTreeDTO>
     */
    public static function toDTOCollection(ArrayCollection $entities): ArrayCollection
    {
        /** @var ArrayCollection<array-key,CategoryTreeDTO> $dtos */
        $dtos = new ArrayCollection();
        foreach ($entities as $entity) {
            $dtos->add(self::toDTO($entity));
        }
        return $dtos;
    }


    public static function toDTO(Item $entity): CategoryTreeDTO
    {
        /** @var ArrayCollection<array-key,CategoryTreeDTO> $children */
        $children = new ArrayCollection();
        foreach ($entity->getChildren() as $child) {
            /** @var Item $child */
            $children->add(self::toDTO($child));
        }

        return new CategoryTreeDTO(
            $entity->getId(),
            $entity->getName(),
            $children,
            $entity->getOwner(),
            $entity->getParentId(),
            $entity->getPlaceholder(),
            $entity->getComment(),
            $entity->getDepth(),
            $entity->isSystem(),
            $entity->isDefault(),
            $entity->getSort(),
            $entity->getCreatedAt(),
            $entity->getUpdatedAt(),
            $entity->getDeletedAt(),
            $entity->getVersion()
        );
    }

    /**
     * @param ArrayCollection<array-key,CategoryTreeDTO> $dtos
     *
     * @return ArrayCollection<array-key,Item>
     */
    public static function toEntityCollection(ArrayCollection $dtos): ArrayCollection
    {
        $entities = new ArrayCollection();
        foreach ($dtos as $dto) {
            $entities->add(self::toEntity($dto));
        }
        return $entities;
    }

    public static function toEntity(CategoryTreeDTO $dto): Item
    {
        $entity = new Item();
        $entity->setId($dto->id);
        $entity->setName($dto->name);
        $entity->setOwner($dto->owner);
        $entity->setPlaceholder($dto->placeholder);
        $entity->setComment($dto->comment);
        $entity->setDepth($dto->depth);
        $entity->setIsSystem($dto->isSystem);
        $entity->setIsDefault($dto->isDefault);
        $entity->setSort($dto->sort);
        $entity->setDeletedAt($dto->deletedAt);

        foreach ($dto->children as $childDto) {
            $childEntity = self::toEntity($childDto);
            $entity->addChild($childEntity);
        }

        return $entity;
    }

}
