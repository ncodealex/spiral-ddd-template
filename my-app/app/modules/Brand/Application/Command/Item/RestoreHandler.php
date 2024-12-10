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
use Modules\Brand\Domain\Exception\Item\ItemNotFoundException;
use Modules\Brand\Domain\Repository\ItemRepositoryInterface;
use SharedCQRS\Brand\Command\BrandRestoreCommand;
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
     * @param BrandRestoreCommand $command
     *
     * @return bool
     * @throws ItemNotFoundException
     */
    #[CommandHandler]
    public function __invoke(BrandRestoreCommand $command): bool
    {
        // Get the entity
        $entity = $this->repository->getByPk($command->id, true);
        // Restore the entity
        $entity->setDeletedAt(null);
        // Save the entity
        return $this->em->persist($entity)->run()->isSuccess();
    }
}
