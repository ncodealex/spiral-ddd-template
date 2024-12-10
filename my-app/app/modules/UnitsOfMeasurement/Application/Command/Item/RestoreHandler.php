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
use Modules\UnitsOfMeasurement\Domain\Exception\Item\ItemNotFoundException;
use Modules\UnitsOfMeasurement\Domain\Repository\ItemRepositoryInterface;
use SharedCQRS\UnitsOfMeasurement\Command\UnitRestoreCommand;
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
     * @param UnitRestoreCommand $command
     *
     * @return bool
     * @throws ItemNotFoundException
     */
    #[CommandHandler]
    public function __invoke(UnitRestoreCommand $command): bool
    {
        // Get the entity
        $entity = $this->repository->getByPk($command->id, true);
        // Restore the entity
        $entity->setDeletedAt(null);
        // Save the entity
        return $this->em->persist($entity)->run()->isSuccess();
    }
}
