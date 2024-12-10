<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Category\Application;

use Cycle\Database\Injection\Fragment;
use Cycle\ORM\EntityManagerInterface;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Transaction\UnitOfWork;
use Doctrine\Common\Collections\ArrayCollection;
use Modules\Category\Domain\CategoryTreeManipulationServiceInterface;
use Modules\Category\Domain\Entity\Item;
use Modules\Category\Domain\Repository\ItemRepositoryInterface;
use Shared\Domain\Utils\NestedSet\NestedSetService;
use Shared\Domain\Utils\NestedSet\NodeInterface;
use Shared\Domain\ValueObject\Category\CategoryOwner;
use Shared\Domain\ValueObject\Uuid;

final readonly class CategoryTreeManipulationService implements CategoryTreeManipulationServiceInterface
{

    public function __construct(
        private ORMInterface             $orm,
        protected EntityManagerInterface $em,
        private ItemRepositoryInterface  $repository,
    )
    {
    }

    public function attachToParent(Item $entity, Uuid $parentId): bool
    {
        $parent = $this->repository->getByPkIncludeChildren($parentId);
        $parent->addChild($entity);
        return $this->em->persist($parent)->run()->isSuccess();
    }

    public function moveToRoot(Item $entity): bool
    {
        $entity->setParent(null);
        $persistRes = $this->em->persist($entity)->run()->isSuccess();
        // recalculate tree
        $rebuildRes = $this->rebuildTree($entity->getOwner());

        return $persistRes && $rebuildRes;
    }

    protected function rebuildTree(CategoryOwner $owner): bool
    {
        /** @var ArrayCollection<array-key,NodeInterface> $nodes */
        $nodes = $this->repository->flat($owner);

        if ($nodes->isEmpty()) {
            return true;
        }

        NestedSetService::calculateNodesFromFlat($nodes);
        $UnitOfWork = new UnitOfWork($this->orm);
        foreach ($nodes as $node) {
            $UnitOfWork->persistDeferred($node, false);
        }
        $result = $UnitOfWork->run()->isSuccess();
        unset($UnitOfWork);
        unset($nodes);
        return $result;
    }

    public function moveToAnotherParent(Item $entity, Uuid $parentId): bool
    {
        $parent = $this->repository->getByPkIncludeChildren($parentId);
        $parent->addChild($entity);
        $persistRes = $this->em->persist($parent)->run()->isSuccess();
        // recalculate tree
        $rebuildRes = $this->rebuildTree($entity->getOwner());
        return $persistRes && $rebuildRes;
    }

    /**
     * @param Item $node
     * @param Item $newParent
     *
     * @deprecated
     */
    protected function moveTo(Item $node, Item $newParent): void
    {
        $left = $node->getLeft();
        $right = $node->getRight();
        $width = $right - $left + 1;

        // Сдвигаем узлы, которые находятся справа от перемещаемого узла
        $this->shiftLeftRight($right + 1, - $width);

        // Обновляем left и right для перемещаемого узла и его потомков
        $newLeft = $newParent->getRight();
        $this->shiftLeftRight($left, $newLeft - $left);

        // Сдвигаем узлы, которые находятся справа от нового местоположения
        $this->shiftLeftRight($newLeft, $width);

        // Обновляем depth для перемещаемого узла и его потомков
    }

    protected function shiftLeftRight(int $start, int $shift): void
    {
        $db = $this->orm->getSource(Item::class)->getDatabase();

        // update left
        $db->update()->set(
            'left', new Fragment('left + :shift', ['shift' => $shift])
        )->where([
            'left' => ['>=' => $start]
        ])->run();

        // update right
        $db->update()->set(
            'right', new Fragment('right + :shift', ['shift' => $shift])
        )->where([
            'right' => ['>=' => $start]
        ])->run();
    }

}
