<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\UnitsOfMeasurement\Application\Command\Item;

use Assert\AssertionFailedException;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Transaction\UnitOfWork;
use Modules\UnitsOfMeasurement\Domain\Entity\Item;
use Modules\UnitsOfMeasurement\Domain\Repository\ItemRepositoryInterface;
use Shared\Domain\Exception\InvalidInputException;
use Shared\Domain\Validation\AppAssert;
use Shared\Domain\ValueObject\Uuid;
use SharedCQRS\UnitsOfMeasurement\Command\UnitsResortCommand;
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
     * @param UnitsResortCommand $command
     *
     * @return bool
     * @throws AssertionFailedException
     */
    #[CommandHandler]
    public function __invoke(UnitsResortCommand $command): bool
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
