<?php
declare(strict_types=1);

namespace Modules\UnitsOfMeasurement\Infrastructure\CycleORM\Specification;

use Modules\UnitsOfMeasurement\Domain\Entity\Item;
use Modules\UnitsOfMeasurement\Domain\Repository\ItemRepositoryInterface;
use Modules\UnitsOfMeasurement\Domain\Specification\ItemIsExistSpecificationInterface;
use Shared\Domain\ValueObject\Uuid;

final class ItemIsExistSpecification implements ItemIsExistSpecificationInterface
{
    public function __construct(
        protected ItemRepositoryInterface $repository
    )
    {
    }

    public function isSatisfiedBy(Uuid $uuid, bool $inTrash = false): bool
    {
        return $this->repository->findOne([
                Item::F_ID => $uuid->getValue(),
                Item::F_DELETED_AT => $inTrash ? ['!=' => null] : null
            ]) !== null;
    }
}
