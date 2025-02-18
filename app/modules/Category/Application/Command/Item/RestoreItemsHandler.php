<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Category\Application\Command\Item;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\Transaction\UnitOfWork;
use Modules\Category\Domain\Entity\Item;
use Modules\Category\Domain\Repository\ItemRepositoryInterface;
use SharedCQRS\Category\Command\CategoriesRestoreCommand;
use Spiral\Cqrs\Attribute\CommandHandler;

final readonly class RestoreItemsHandler
{
    public function __construct(
        private ORMInterface            $orm,
        private ItemRepositoryInterface $repository,
    )
    {
    }

    /**
     * Restore the entity from trash
     *
     * @param CategoriesRestoreCommand $command
     *
     * @return bool
     */
    #[CommandHandler]
    public function __invoke(CategoriesRestoreCommand $command): bool
    {
        $UoW = new UnitOfWork($this->orm);
        /** @var Item[] $entities */
        $entities = $this->repository->findByIds(ids: $command->ids, withTrash: true);
        foreach ($entities as $entity) {
            $entity->setDeletedAt(null);
            $UoW->persistState($entity, false);
        }
        return $UoW->run()->isSuccess();
    }
}
