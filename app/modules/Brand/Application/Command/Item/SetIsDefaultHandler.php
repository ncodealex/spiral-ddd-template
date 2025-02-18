<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Brand\Application\Command\Item;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\Transaction\UnitOfWork;
use Modules\Brand\Domain\Repository\ItemRepositoryInterface;
use Modules\Brand\Infrastructure\CycleORM\Table\ItemTable;
use SharedCQRS\Brand\Command\BrandSetDefaultCommand;
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
     * @param BrandSetDefaultCommand $command
     *
     * @return bool
     */
    #[CommandHandler]
    public function __invoke(BrandSetDefaultCommand $command): bool
    {

        // Get the entity
        $entity = $this->repository->getByPk($command->id);
        $collection = $this->repository->findAll([
            ItemTable::OWNER => $command->owner->getValue(),
            ItemTable::ID => ['!=' => $command->id->getValue()],
            ItemTable::IS_DEFAULT => true,
        ]);
        $UoW = new UnitOfWork($this->orm);
        foreach ($collection as $item) {
            $item->setIsDefault(false);
            $UoW->persistDeferred($item, false);
        }
        $entity->setIsDefault(true);
        $UoW->persistDeferred($entity, false);
        return $UoW->run()->isSuccess();
    }
}
