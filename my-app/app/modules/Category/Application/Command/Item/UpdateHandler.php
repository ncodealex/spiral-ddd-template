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
use Modules\Category\Domain\Factory\ItemFactoryInterface;
use Modules\Category\Domain\Repository\ItemRepositoryInterface;
use SharedCQRS\Category\Command\CategoryPatchCommand;
use Spiral\Cqrs\Attribute\CommandHandler;
use Spiral\Cqrs\CommandBusInterface;

final readonly class UpdateHandler
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
     * @param CategoryPatchCommand $command
     *
     * @return bool
     */
    #[CommandHandler]
    public function __invoke(CategoryPatchCommand $command): bool
    {
        // Find the entity
        $entity = $this->repository->getByPk($command->id);
        // Update the entity
        $entity = ItemMapper::updateEntity($entity, $command);
        // Save the entity
        return $this->em->persist($entity, false)->run()->isSuccess();
    }
}
