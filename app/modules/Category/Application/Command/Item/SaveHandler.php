<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Category\Application\Command\Item;

use Cycle\ORM\EntityManagerInterface;
use Modules\Category\Application\Mapper\ItemMapper;
use Modules\Category\Application\NodeService;
use Modules\Category\Domain\CategoryTreeManipulationServiceInterface;
use Modules\Category\Domain\Exception\Item\ItemNotFoundException;
use Modules\Category\Domain\Factory\ItemFactoryInterface;
use Modules\Category\Domain\Repository\ItemRepositoryInterface;
use Shared\Domain\ValueObject\Uuid;
use SharedCQRS\Category\Command\CategorySaveCommand;
use SharedCQRS\Category\Command\CategorySetDefaultCommand;
use Spiral\Cqrs\Attribute\CommandHandler;
use Spiral\Cqrs\CommandBusInterface;

final readonly class SaveHandler
{
    public function __construct(
        protected EntityManagerInterface                   $em,
        protected ItemRepositoryInterface                  $repository,
        protected ItemFactoryInterface                     $factory,
        protected CommandBusInterface                      $bus,
        protected CategoryTreeManipulationServiceInterface $nodeService
    )
    {
    }


    /**
     *  Save the entity
     *
     * @param CategorySaveCommand $command
     *
     * @return Uuid|false
     * @throws ItemNotFoundException
     */
    #[CommandHandler]
    public function __invoke(CategorySaveCommand $command): Uuid|false
    {
        // Create or update the entity
        if ($command->id === null) {
            /**
             *  CREATE entity
             */
            $entity = ItemMapper::toEntity($this->factory, $command);
            // command->parentId != null attach to parent
            if ($command->parentId !== null) {
                $saveResult = $this->nodeService->attachToParent($entity, $command->parentId);
            } else {
                $saveResult = $this->em->persist($entity)->run()->isSuccess();
            }
        } else {
            /**
             *  UPDATE entity
             */
            // Get the entity
            $entity = $this->repository->getByPk($command->id);
            $entity = ItemMapper::putEntity($entity, $command);
            /**
             *  Operation with parent
             */
            // in DB parent = null, and command->parentId != null attach to parent
            if ($entity->getParentId() === null && $command->parentId !== null) {
                $saveResult = $this->nodeService->attachToParent($entity, $command->parentId);
            } elseif ($entity->getParentId() !== null && $command->parentId === null) {
                // in DB parent != null, and command->parentId = null move to root
                $saveResult = $this->nodeService->moveToRoot($entity);
            } elseif ($entity->getParentId() !== null && $command->parentId !== null && $entity->getParentId() !== $command->parentId) {
                // in DB parent != null, and command->parentId != null move to another parent
                $saveResult = $this->nodeService->moveToAnotherParent($entity, $command->parentId);
            } else {
                $saveResult = $this->em->persist($entity)->run()->isSuccess();
            }
        }
        if ($command->isDefault && $saveResult) {
            // Need set is default
            $setIsDefResult = $this->bus->dispatch(new CategorySetDefaultCommand(
                $entity->getId(),
                $entity->getOwner()
            ));
            return $setIsDefResult ? $entity->getId() : false;
        }


        return $saveResult ? $entity->getId() : false;

    }
}
