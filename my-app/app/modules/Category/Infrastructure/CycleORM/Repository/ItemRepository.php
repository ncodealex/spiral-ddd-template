<?php
declare(strict_types=1);

namespace Modules\Category\Infrastructure\CycleORM\Repository;

use Cycle\ORM\Select\Repository;
use Doctrine\Common\Collections\ArrayCollection;
use Modules\Category\Domain\Entity\Item;
use Modules\Category\Domain\Exception\ItemExceptions;
use Modules\Category\Domain\Repository\ItemRepositoryInterface;
use Shared\Domain\Exception\InvalidInputException;
use Shared\Domain\ValueObject\Category\CategoryOwner;
use Shared\Domain\ValueObject\Uuid;
use Shared\Infrastructure\ValueObject\Sort;

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
     * @param bool $inTrash
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
    public function getByPkIncludeChildren(Uuid $id, bool $inTrash = false, bool $inTrashChildren = false): Item
    {
        return $this->select()->where(
            [
                Item::F_ID => $id->getValue(),
                Item::F_DELETED_AT => $inTrash ? ['!=' => null] : null
            ]
        )->load('children', [
            'where' => [
                Item::F_DELETED_AT => $inTrashChildren ? ['!=' => null] : null
            ],
            'orderBy' => [
                Item::F_LEFT => Sort::ASC
            ]
        ])->fetchOne() ?? ItemExceptions::notFound();
    }

    /**
     * @inheritDoc
     */
    public function findAllByOwner(CategoryOwner $owner, bool $inTrash = false, bool $toData = false): ArrayCollection
    {
        $select = $this->select()->where([
            Item::F_OWNER => $owner->getValue(),
            Item::F_DELETED_AT => $inTrash ? ['!=' => null] : null
        ])->orderBy([
            Item::F_LEFT => Sort::ASC,
            Item::F_DEPTH => Sort::ASC
        ]);
        if ($toData) {
            return new ArrayCollection($select->fetchData());
        } else {
            return new ArrayCollection($select->fetchAll());
        }
    }

    /**
     * @inheritDoc
     */
    public function tree(CategoryOwner $owner): ArrayCollection
    {
        /** @var Item[] $result */
        $result = $this->select()->with('children')->where([
            Item::F_OWNER => $owner->getValue(),
            Item::F_PARENT_ID => null
        ])->orderBy([
            Item::F_LEFT => Sort::ASC,
            Item::F_DEPTH => Sort::ASC
        ])->fetchAll();
        return new ArrayCollection($result);
    }

    /**
     * @inheritDoc
     * @psalm-suppress InvalidReturnStatement
     * @psalm-suppress InvalidReturnType
     */
    public function flat(CategoryOwner $owner, bool $toData = false): ArrayCollection
    {
        $select = $this->select()->where([
            Item::F_OWNER => $owner->getValue()
        ])->orderBy([
            Item::F_LEFT => Sort::ASC,
            Item::F_DEPTH => Sort::ASC
        ]);
        if ($toData) {
            $result = $select->fetchData();
        } else {
            $result = $select->fetchAll();
        }
        return new ArrayCollection($result);
    }
}
