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
use SharedCQRS\UnitsOfMeasurement\Command\UnitDeleteCommand;
use Spiral\Cqrs\Attribute\CommandHandler;

final readonly class SafeDeleteHandler
{
    public function __construct(
        protected EntityManagerInterface  $em,
        protected ItemRepositoryInterface $repository
    )
    {
    }

    /**
     * Safe delete the entity
     *
     * @param UnitDeleteCommand $command
     *
     * @return bool
     * @throws ItemNotFoundException
     */
    #[CommandHandler]
    public function __invoke(UnitDeleteCommand $command): bool
    {
        // Find the entity
        $entity = $this->repository->getByPk($command->id);
        // Safe delete the entity
        return $this->em->delete($entity, false)->run()->isSuccess();
    }
}
