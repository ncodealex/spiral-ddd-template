<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Brand\Application\Command\Item;

use Cycle\ORM\EntityManagerInterface;
use Modules\Brand\Application\Mapper\ItemMapper;
use Modules\Brand\Domain\Exception\Item\ItemNotFoundException;
use Modules\Brand\Domain\Factory\ItemFactoryInterface;
use Modules\Brand\Domain\Repository\ItemRepositoryInterface;
use Shared\Domain\ValueObject\Uuid;
use SharedCQRS\Brand\Command\BrandSaveCommand;
use SharedCQRS\Brand\Command\BrandSetDefaultCommand;
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
     * @param BrandSaveCommand $command
     *
     * @return Uuid|false
     * @throws ItemNotFoundException
     */
    #[CommandHandler]
    public function __invoke(BrandSaveCommand $command): Uuid|false
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
            $setIsDefResult = $this->bus->dispatch(new BrandSetDefaultCommand(
                $entity->getId(),
                $entity->getOwner()
            ));
            return $setIsDefResult ? $entity->getId() : false;
        }

        return $saveResult ? $entity->getId() : false;

    }
}
