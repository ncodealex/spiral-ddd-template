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
use Modules\Category\Domain\Exception\Item\ItemNotFoundException;
use Modules\Category\Domain\Repository\ItemRepositoryInterface;
use SharedCQRS\Category\Command\CategoryRestoreCommand;
use Spiral\Cqrs\Attribute\CommandHandler;

final readonly class RestoreHandler
{
    public function __construct(
        protected EntityManagerInterface  $em,
        protected ItemRepositoryInterface $repository
    )
    {
    }

    /**
     * Restore the entity from trash
     *
     * @param CategoryRestoreCommand $command
     *
     * @return bool
     * @throws ItemNotFoundException
     */
    #[CommandHandler]
    public function __invoke(CategoryRestoreCommand $command): bool
    {
        // Get the entity
        $entity = $this->repository->getByPk($command->id, true);
        // Restore the entity
        $entity->setDeletedAt(null);
        // Save the entity
        return $this->em->persist($entity)->run()->isSuccess();
    }
}
