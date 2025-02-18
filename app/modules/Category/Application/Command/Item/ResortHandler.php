<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Category\Application\Command\Item;

use Assert\AssertionFailedException;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Transaction\UnitOfWork;
use Modules\Category\Domain\Entity\Item;
use Modules\Category\Domain\Repository\ItemRepositoryInterface;
use Shared\Domain\Exception\InvalidInputException;
use Shared\Domain\Validation\AppAssert;
use Shared\Domain\ValueObject\Uuid;
use SharedCQRS\Category\Command\CategoriesResortCommand;
use Spiral\Cqrs\Attribute\CommandHandler;

final readonly class ResortHandler
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
     * @param CategoriesResortCommand $command
     *
     * @return bool
     * @throws AssertionFailedException
     */
    #[CommandHandler]
    public function __invoke(CategoriesResortCommand $command): bool
    {
        // Validate the command
        AppAssert::notEmpty($command->ids);
        $ids = $command->ids;
        $UoW = new UnitOfWork($this->orm);
        /** @var Item[] $entities */
        $entities = $this->repository->findByIds($ids);
        $i = 1;
        foreach ($command->ids as $id) {
            if (!$id instanceof Uuid) {
                throw new InvalidInputException('id not instance of UUID');
            }
            foreach ($entities as $entity) {
                if ($entity->getId()->getValue() === $id->getValue()) {
                    $entity->setSort($i);
                    $UoW->persistState($entity, false);
                    $i ++;
                }
            }
        }
        return $UoW->run()->isSuccess();
    }
}
