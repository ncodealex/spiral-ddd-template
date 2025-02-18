<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Group\Application\Command\Item;

use Cycle\ORM\EntityManagerInterface;
use Modules\Group\Application\Mapper\ItemMapper;
use Modules\Group\Domain\Repository\ItemRepositoryInterface;
use SharedCQRS\Group\Command\GroupPatchCommand;
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
     * @param GroupPatchCommand $command
     *
     * @return bool
     */
    #[CommandHandler]
    public function __invoke(GroupPatchCommand $command): bool
    {
        // Find the entity
        $entity = $this->repository->getByPk($command->id);
        // Update the entity
        $entity = ItemMapper::updateEntity($entity, $command);
        // Save the entity
        return $this->em->persist($entity)->run()->isSuccess();
    }
}
