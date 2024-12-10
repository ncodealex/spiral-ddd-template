<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Group\Application\Command\Item;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\Transaction\UnitOfWork;
use Modules\Group\Domain\Entity\Item;
use Modules\Group\Domain\Repository\ItemRepositoryInterface;
use SharedCQRS\Group\Command\GroupSetDefaultCommand;
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
     * @param GroupSetDefaultCommand $command
     *
     * @return bool
     */
    #[CommandHandler]
    public function __invoke(GroupSetDefaultCommand $command): bool
    {

        // Get the entity
        $entity = $this->repository->getByPk($command->id);
        $collection = $this->repository->findAll([
            Item::F_OWNER => $command->owner->getValue(),
            Item::F_ID => ['!=' => $command->id->getValue()],
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
