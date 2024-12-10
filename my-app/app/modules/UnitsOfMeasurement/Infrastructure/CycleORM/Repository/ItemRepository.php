<?php
declare(strict_types=1);

namespace Modules\UnitsOfMeasurement\Infrastructure\CycleORM\Repository;

use Cycle\ORM\Select\Repository;
use Modules\UnitsOfMeasurement\Domain\Entity\Item;
use Modules\UnitsOfMeasurement\Domain\Exception\ItemExceptions;
use Modules\UnitsOfMeasurement\Domain\Repository\ItemRepositoryInterface;
use Shared\Domain\Exception\InvalidInputException;
use Shared\Domain\ValueObject\Uuid;

/**
 * @template TEntity of Item
 * @extends Repository<Item>
 */
final class ItemRepository extends Repository implements ItemRepositoryInterface
{

    /**
     * @param Uuid $id
     * @param bool $inTrash *
     *
     * @inheritDoc
     * @psalm-suppress NullableReturnStatement
     * @psalm-suppress InvalidNullableReturnType
     */
    public function getByPk(Uuid $id, bool $inTrash = false): Item
    {
        return $this->findOneByPk($id, $inTrash) ?? ItemExceptions::notFound();
    }

    /**
     * @param Uuid $id
     * @param bool $inTrash *
     *
     * @inheritDoc
     */
    public function findOneByPk(Uuid $id, bool $inTrash = false): ?Item
    {
        return $this->findOne([
            Item::F_ID => $id->getValue(),
            Item::F_DELETED_AT => $inTrash ? ['!=' => null] : null
        ]);
    }

    /**
     * @inheritDoc
     */
    public function findByIds(
        array  $ids,
        ?array $scope = [],
        ?array $orderBy = [],
        ?bool  $withTrash = false,
    ): iterable
    {
        $in = [];
        foreach ($ids as $id) {
            if (!$id instanceof Uuid) {
                throw new InvalidInputException('id not instance of UUID');
            }
            $in[] = $id->getValue();
        }
        if (empty($in)) {
            return [];
        }
        $trashScope = [];
        if (!$withTrash) {
            $trashScope = [
                Item::F_DELETED_AT => null
            ];
        }

        $scope = array_merge([
            Item::F_ID => ['in' => $in],
        ], $scope ?? [], $trashScope);

        return $this->findAll($scope, $orderBy ?? []);
    }
}
