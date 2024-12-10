<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\UnitsOfMeasurement\Application\Mapper;

use Modules\UnitsOfMeasurement\Domain\Entity\Item;
use Modules\UnitsOfMeasurement\Domain\Factory\ItemFactoryInterface;
use Shared\Domain\ValueObject\Uuid;
use SharedCQRS\UnitsOfMeasurement\Command\UnitPatchCommand;
use SharedCQRS\UnitsOfMeasurement\Command\UnitSaveCommand;
use SharedCQRS\UnitsOfMeasurement\UnitDTO;

final readonly class ItemMapper
{
    public static function toDTO(Item $entity): UnitDTO
    {
        return new UnitDTO(
            $entity->getId(),
            $entity->getName(),
            $entity->getPrecision(),
            $entity->getForPackaging(),
            $entity->isSystem(),
            $entity->isDefault(),
            $entity->getSort(),
            $entity->getCreatedAt(),
            $entity->getUpdatedAt(),
            $entity->getDeletedAt(),
            $entity->getVersion()
        );
    }

    public static function toEntity(ItemFactoryInterface $factory, UnitSaveCommand $command): Item
    {
        $entity = $factory->create(
            $command->name,
            $command->precision,
            $command->packagingType,
            $command->isDefault
        );
        if ($command->id === null) {
            $entity->setId(Uuid::generate());
        } else {
            $entity->setId($command->id);
        }
        return $entity;

    }

    public static function putEntity(Item $entity, UnitSaveCommand $command): Item
    {
        $entity->setName($command->name);
        $entity->setPrecision($command->precision);
        $entity->setForPackaging($command->packagingType);
        $entity->setIsDefault($command->isDefault);
        $entity->setSort($command->sort);
        return $entity;
    }

    public static function updateEntity(Item $entity, UnitPatchCommand $command): Item
    {
        foreach (get_object_vars($command) as $property => $value) {
            if ($value !== null) {
                /** @psalm-suppress MixedArgument */
                match ($property) {
                    Item::F_NAME => $entity->setName($value),
                    Item::F_PRECISION => $entity->setPrecision($value),
                    Item::F_FOR_PACKAGING => $entity->setForPackaging($value),
                    Item::F_IS_DEFAULT => $entity->setIsDefault($value),
                    Item::F_SORT => $entity->setSort($value),
                    default => null,
                };
            }
        }
        return $entity;
    }


}
