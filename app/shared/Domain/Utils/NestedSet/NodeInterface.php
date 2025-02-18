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
use Shared\Domain\ValueObject\Uuid;

interface NodeInterface
{

    public function getId(): int|Uuid;

    public function getParentId(): null|int|Uuid;

    public function getLeft(): int;

    public function setLeft(int $left): void;

    public function getRight(): int;

    public function setRight(int $right): void;

    public function getDepth(): int;

    public function setDepth(int $depth): void;

    /**
     * @return ArrayCollection<array-key,NodeInterface>
     */
    public function getChildren(): ArrayCollection;

    public function addChild(self $child): void;

    public function clearChildren(): self;

    public function treeNodeParamWasUpdated(): void;
}
