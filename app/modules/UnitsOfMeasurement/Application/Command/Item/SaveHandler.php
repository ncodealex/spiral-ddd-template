<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\UnitsOfMeasurement\Application\Command\Item;

use Cycle\ORM\EntityManagerInterface;
use Modules\UnitsOfMeasurement\Application\Mapper\ItemMapper;
use Modules\UnitsOfMeasurement\Domain\Exception\Item\ItemNotFoundException;
use Modules\UnitsOfMeasurement\Domain\Factory\ItemFactoryInterface;
use Modules\UnitsOfMeasurement\Domain\Repository\ItemRepositoryInterface;
use Shared\Domain\ValueObject\Uuid;
use SharedCQRS\UnitsOfMeasurement\Command\UnitSaveCommand;
use SharedCQRS\UnitsOfMeasurement\Command\UnitSetDefaultCommand;
use Spiral\Cqrs\Attribute\CommandHandler;
use Spiral\Cqrs\CommandBusInterface;

final readonly class SaveHandler
{
    public function __construct(
        protected EntityManagerInterface  $em,
        protected ItemRepositoryInterface $repository,
        protected ItemFactoryInterface    $factory,
        protected CommandBusInterface     $bus
    )
    {
    }


    /**
     *  Save the entity
     *
     * @param UnitSaveCommand $command
     *
     * @return Uuid|false
     * @throws ItemNotFoundException
     */
    #[CommandHandler]
    public function __invoke(UnitSaveCommand $command): Uuid|false
    {
        // Create or update the entity
        if ($command->id === null) {
            // Create the entity
            $entity = ItemMapper::toEntity($this->factory, $command);
        } else {
            // Get the entity
            $entity = $this->repository->getByPk($command->id);
            // Update the entity
            $entity = ItemMapper::putEntity($entity, $command);
        }
        // Save the entity
        $saveResult = $this->em->persist($entity)->run()->isSuccess();

        if ($command->isDefault && $saveResult) {
            // Need set is default
            $setIsDefResult = $this->bus->dispatch(new UnitSetDefaultCommand(
                $entity->getId()
            ));
            return $setIsDefResult ? $entity->getId() : false;
        }

        return $saveResult ? $entity->getId() : false;

    }
}
