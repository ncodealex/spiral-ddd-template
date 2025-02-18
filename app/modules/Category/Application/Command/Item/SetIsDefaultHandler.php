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
use SharedCQRS\Category\Command\CategorySetDefaultCommand;
use Spiral\Cqrs\Attribute\CommandHandler;

final readonly class SetIsDefaultHandler
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
     * @param CategorySetDefaultCommand $command
     *
     * @return bool
     */
    #[CommandHandler]
    public function __invoke(CategorySetDefaultCommand $command): bool
    {
        // Get the entity
        $entity = $this->repository->getByPk($command->id);
        $collection = $this->repository->findAll([
            Item::F_OWNER => $entity->getOwner(),
            Item::F_ID => ['!=' => $entity->getId()],
            Item::F_IS_DEFAULT => true,
        ]);
        $UoW = new UnitOfWork($this->orm);
        foreach ($collection as $Item) {
            $Item->setIsDefault(false);
            $UoW->persistDeferred($Item, false);
        }
        $entity->setIsDefault(true);
        $UoW->persistDeferred($entity, false);
        return $UoW->run()->isSuccess();
    }
}
