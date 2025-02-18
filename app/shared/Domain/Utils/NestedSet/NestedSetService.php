<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Domain\Utils\NestedSet;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Shared\Domain\Aggregate\AggregateRootCollection;
use Shared\Domain\Utils\NestedSet\Exception\IsNotNodeException;

final class NestedSetService
{

    /**
     * Calculate nodes from flat collection
     *
     * @param Collection<array-key,NodeInterface>|array<array-key,NodeInterface> $items
     *
     * @throws IsNotNodeException
     *
     */
    public static function calculateNodesFromFlat(Collection|array $items): void
    {
        if (is_array($items)) {
            $items = new ArrayCollection($items);
        }
        if ($items->isEmpty()) {
            return;
        }
        self::validateCollection($items);
        $tree = self::toTree($items);
        self::calculateNodes($tree);
        unset($tree);
        unset($items);
    }

    /**
     * Validate collection
     *
     * @param Collection<array-key,NodeInterface> $items
     *
     * @throws IsNotNodeException
     */
    private static function validateCollection(Collection $items): void
    {
        if (!$items->first() instanceof NodeInterface) {
            throw new IsNotNodeException();
        }
    }

    /**
     *  To tree flat collection
     *
     * @param Collection<array-key,NodeInterface>|NodeInterface[] $items
     *
     * @return ArrayCollection|AggregateRootCollection
     */
    public static function toTree(Collection|array $items): ArrayCollection|AggregateRootCollection
    {
        if (is_array($items)) {
            $items = new ArrayCollection((array)$items);
        }
        /** @psalm-suppress */
        self::validateCollection($items);
        /** @var ArrayCollection<array-key,NodeInterface> | AggregateRootCollection<NodeInterface> $tree */
        $tree = $items instanceof AggregateRootCollection ? new AggregateRootCollection() : new ArrayCollection();

        $parents = $items->filter(fn (NodeInterface $item) => $item->getParentId() === null);

        if ($parents->isEmpty()) {
            return $tree;
        }
        /** @var NodeInterface $parent */
        foreach ($parents as $parent) {
            $items->removeElement($parent);
            $tree->add($parent);
            self::buildTreeRecursive($items, $parent);
        }
        return $tree;
    }

    protected static function buildTreeRecursive(ArrayCollection $items, NodeInterface $parent): void
    {
        $children = $items->filter(fn (NodeInterface $item) => $item->getParentId() === $parent->getId());
        /** @var NodeInterface $child */
        foreach ($children as $child) {
            $parent->addChild($child);
            $items->removeElement($child);
            self::buildTreeRecursive($items, $child);
        }
    }

    /**
     * Calculate nodes from tree collection
     *
     * @param ArrayCollection<array-key,NodeInterface> $tree
     *
     * @throws IsNotNodeException
     */
    public static function calculateNodes(Collection $tree): void
    {
        if ($tree->isEmpty()) {
            return;
        }
        self::validateCollection($tree);
        $left = 1;
        foreach ($tree as $item) {
            $item->setLeft($left);
            $item->setDepth(0);
            $right = self::calculateSiblings($item);
            $item->setRight($right);
            $left = $right + 1;
            $item->treeNodeParamWasUpdated();
        }
        unset($tree);
    }

    /**
     * @param NodeInterface $parent
     *
     * @return int
     */
    protected static function calculateSiblings(NodeInterface $parent): int
    {
        $left = $parent->getLeft() + 1;
        $right = $left;
        foreach ($parent->getChildren() as $child) {
            $child->setDepth($parent->getDepth() + 1);
            $child->setLeft($left);
            $right = self::calculateSiblings($child);
            $child->setRight($right);
            $left = $right + 1;
            $right ++;
            $child->treeNodeParamWasUpdated();
        }
        return $right;
    }

    /**
     * @param ArrayCollection<array-key,NodeInterface>|array $items
     *
     * @return ArrayCollection<array-key,NodeInterface>
     */
    public static function toFlat(ArrayCollection|array $items): ArrayCollection
    {
        if (is_array($items)) {
            $items = new ArrayCollection($items);
        }

        self::validateCollection($items);

        /** @var ArrayCollection<array-key,NodeInterface> $result */
        $result = new ArrayCollection();
        foreach ($items as $item) {
            $result->add($item);
            foreach (self::toFlat($item->getChildren()) as $child) {
                $result->add($child);
            }
            $item->clearChildren();
        }
        return $result;
    }
}
