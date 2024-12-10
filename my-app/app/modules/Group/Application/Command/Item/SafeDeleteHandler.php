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
use Modules\Group\Domain\Exception\Item\ItemNotFoundException;
use Modules\Group\Domain\Repository\ItemRepositoryInterface;
use SharedCQRS\Group\Command\GroupDeleteCommand;
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
     * @param GroupDeleteCommand $command
     *
     * @return bool
     * @throws ItemNotFoundException
     */
    #[CommandHandler]
    public function __invoke(GroupDeleteCommand $command): bool
    {
        // Find the entity
        $entity = $this->repository->getByPk($command->id);
        // Safe delete the entity
        return $this->em->delete($entity, false)->run()->isSuccess();
    }
}
