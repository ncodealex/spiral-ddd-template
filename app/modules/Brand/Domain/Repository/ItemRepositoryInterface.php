<?php
declare(strict_types=1);

namespace Modules\Brand\Domain\Repository;

use Cycle\ORM\RepositoryInterface;
use Modules\Brand\Domain\Entity\Item;
use Modules\Brand\Domain\Exception\Item\ItemNotFoundException;
use Shared\Domain\ValueObject\Uuid;

/**
 * @extends RepositoryInterface<Item>
 */
interface ItemRepositoryInterface extends RepositoryInterface
{

    /**
     * @param Uuid $id
     * @param bool $inTrash
     *
     * @return Item|null
     */
    public function findOneByPk(Uuid $id, bool $inTrash = false): ?Item;

    /**
     *  Get by primary key
     *
     * @param Uuid $id
     * @param bool $inTrash
     *
     * @return Item
     * @throws ItemNotFoundException
     */
    public function getByPk(Uuid $id, bool $inTrash = false): Item;

    /**
     * @param Uuid[]                    $ids
     * @param array|null                $scope
     * @param array<string,string>|null $orderBy
     * @param bool|null                 $withTrash
     *
     * @return iterable<Item>
     */
    public function findByIds(
        array  $ids,
        ?array $scope = [],
        ?array $orderBy = [],
        ?bool  $withTrash = false,
    ): iterable;

    /**
     * Return all data as array
     * without binding to the entity
     *
     * @param array|null $scope
     * @param array|null $orderBy
     * @param bool|null  $withTrash
     *
     * @return iterable
     */
    public function getDataAll(
        ?array $scope = [],
        ?array $orderBy = [],
        ?bool  $withTrash = false
    ): iterable;
}
