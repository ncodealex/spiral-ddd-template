<?php
declare(strict_types=1);

namespace Modules\Brand\Infrastructure\CycleORM\Repository;

use Cycle\ORM\Select\Repository;
use Modules\Brand\Domain\Entity\Item;
use Modules\Brand\Domain\Exception\ItemExceptions;
use Modules\Brand\Domain\Repository\ItemRepositoryInterface;
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

    /**
     * @inheritDoc
     */
    public function getDataAll(
        ?array $scope = [],
        ?array $orderBy = [],
        ?bool  $withTrash = false
    ): iterable
    {
        return $this->select()->where([
            Item::F_DELETED_AT => $withTrash ? ['!=' => null] : null
        ])->fetchData(false);
    }

}
