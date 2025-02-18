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


trait NodeTrait
{

    private int $left = 0;
    private int $right = 0;
    private int $depth = 0;
    private ArrayCollection $children;

    public function getLeft(): int
    {
        return $this->left;
    }

    public function setLeft(int $left): void
    {
        $this->left = $left;
    }

    public function getRight(): int
    {
        return $this->right;
    }

    public function setRight(int $right): void
    {
        $this->right = $right;
    }

    public function getDepth(): int
    {
        return $this->depth;
    }

    public function setDepth(int $depth): void
    {
        $this->depth = $depth;
    }

    /**
     * @return ArrayCollection<array-key,NodeInterface>
     */
    public function getChildren(): ArrayCollection
    {
        return $this->children;
    }
}
