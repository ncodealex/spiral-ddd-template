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
use Modules\UnitsOfMeasurement\Domain\Repository\ItemRepositoryInterface;
use SharedCQRS\UnitsOfMeasurement\Command\UnitPatchCommand;
use Spiral\Cqrs\Attribute\CommandHandler;

final readonly class UpdateHandler
{
    public function __construct(
        protected EntityManagerInterface  $em,
        protected ItemRepositoryInterface $repository
    )
    {
    }


    /**
     *  Save the entity
     *
     * @param UnitPatchCommand $command
     *
     * @return bool
     */
    #[CommandHandler]
    public function __invoke(UnitPatchCommand $command): bool
    {
        // Find the entity
        $entity = $this->repository->getByPk($command->id);
        // Update the entity
        $entity = ItemMapper::updateEntity($entity, $command);
        // Save the entity
        return $this->em->persist($entity)->run()->isSuccess();
    }
}
