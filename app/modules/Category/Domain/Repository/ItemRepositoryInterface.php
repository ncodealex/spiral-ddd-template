<?php
declare(strict_types=1);

namespace Modules\Category\Domain\Repository;

use Cycle\ORM\RepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Modules\Category\Domain\Entity\Item;
use Modules\Category\Domain\Exception\Item\ItemNotFoundException;
use Shared\Domain\ValueObject\Category\CategoryOwner;
use Shared\Domain\ValueObject\Uuid;

/**
 * @extends RepositoryInterface<Item>
 */
interface ItemRepositoryInterface extends RepositoryInterface
{

    /**
     * @param CategoryOwner $owner
     * @param bool          $inTrash
     * @param bool          $toData
     *
     * @return ArrayCollection<array-key,Item|array>
     */
    public function findAllByOwner(CategoryOwner $owner, bool $inTrash = false, bool $toData = false): ArrayCollection;

    /**
     * @param CategoryOwner $owner
     *
     * @return ArrayCollection<array-key,Item>
     */
    public function tree(CategoryOwner $owner): ArrayCollection;

    /**
     * @param CategoryOwner $owner
     *
     * @return ArrayCollection<array-key,Item>
     */
    public function flat(CategoryOwner $owner): ArrayCollection;

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
     *  Get by primary key
     *
     * @param Uuid $id
     * @param bool $inTrash
     * @param bool $inTrashChildren
     *
     * @return Item
     * @throws ItemNotFoundException
     */
    public function getByPkIncludeChildren(Uuid $id, bool $inTrash = false, bool $inTrashChildren = false): Item;

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
}
